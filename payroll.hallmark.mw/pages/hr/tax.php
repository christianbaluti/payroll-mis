<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Tax Calculator</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center p-4">
  <div class="bg-white shadow-2xl rounded-2xl p-8 w-full max-w-2xl">
    <h2 class="text-2xl font-bold text-center mb-6 text-blue-700">Tax Calculator</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
      <!-- Gross → Net -->
      <div class="space-y-4">
        <h3 class="text-lg font-semibold text-gray-700">🧮 Gross → Net</h3>
        <input 
          type="number" 
          id="grossInput" 
          placeholder="Enter your Gross Salary"
          class="w-full p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500"
        >
        <button 
          onclick="calculateFromGross()" 
          class="w-full bg-blue-600 text-white font-semibold py-2 rounded-lg hover:bg-blue-700 transition-all">
          Calculate Net
        </button>
        <div id="grossResult" class="text-sm bg-gray-50 p-4 rounded-lg border border-gray-200 space-y-2 min-h-[100px]"></div>
      </div>

      <!-- Net → Gross -->
      <div class="space-y-4">
        <h3 class="text-lg font-semibold text-gray-700">🔁 Net → Gross</h3>
        <input 
          type="number" 
          id="netInput" 
          placeholder="Enter your Desired Net Salary"
          class="w-full p-3 rounded-lg border border-gray-300 focus:outline-none focus:ring-2 focus:ring-green-500"
        >
        <button 
          onclick="estimateGrossFromNet()" 
          class="w-full bg-green-600 text-white font-semibold py-2 rounded-lg hover:bg-green-700 transition-all">
          Estimate Gross
        </button>
        <div id="netResult" class="text-sm bg-gray-50 p-4 rounded-lg border border-gray-200 space-y-2 min-h-[100px]"></div>
      </div>
    </div>
  </div>

  <script>
    function showLoading(container) {
      container.innerHTML = `
        <div class="flex items-center gap-3 text-blue-600">
          <svg class="animate-spin h-5 w-5 text-blue-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path>
          </svg>
          <span>Calculating, please wait...</span>
        </div>
      `;
    }

    function calculateTax(gross) {
      let remaining = gross, totalTax = 0, breakdown = [];

      if (remaining <= 150000) {
        breakdown.push({ range: "K0–150,000", rate: "0%", tax: 0 });
        return { totalTax: 0, breakdown };
      }
      breakdown.push({ range: "K0–150,000", rate: "0%", tax: 0 });
      remaining -= 150000;

      if (remaining >= 350000) {
        const t = 350000 * .25;
        totalTax += t;
        breakdown.push({ range: "150,001–500,000", rate: "25%", tax: t });
        remaining -= 350000;
      } else {
        const t = remaining * .25;
        totalTax += t;
        breakdown.push({ range: `150,001–${150000+remaining}`, rate: "25%", tax: t });
        return { totalTax, breakdown };
      }

      if (remaining >= 2050000) {
        const t = 2050000 * .30;
        totalTax += t;
        breakdown.push({ range: "500,001–2,550,000", rate: "30%", tax: t });
        remaining -= 2050000;
      } else {
        const t = remaining * .30;
        totalTax += t;
        breakdown.push({ range: `500,001–${gross}`, rate: "30%", tax: t });
        return { totalTax, breakdown };
      }

      if (remaining > 0) {
        const t = remaining * .35;
        totalTax += t;
        breakdown.push({ range: "2,550,001+", rate: "35%", tax: t });
      }
      return { totalTax, breakdown };
    }

    function marginalRate(gross) {
      if (gross <= 150000) return 0;
      if (gross <= 500000) return 0.25;
      if (gross <= 2550000) return 0.30;
      return 0.35;
    }

    function calculateFromGross() {
      const g = parseFloat(document.getElementById("grossInput").value);
      const out = document.getElementById("grossResult");
      if (isNaN(g) || g <= 0) {
        out.innerHTML = `<p class="text-red-500">⚠️ Enter a valid gross salary.</p>`;
        return;
      }
      showLoading(out);
      setTimeout(() => {
        const { totalTax, breakdown } = calculateTax(g);
        const net = g - totalTax;
        let html = breakdown.map(b =>
            `<p><strong>${b.range}</strong> @ ${b.rate} = <span class="text-blue-700">K${b.tax.toLocaleString()}</span></p>`
          ).join("");
        html += `<hr class="my-2">
                 <p><strong>Total Tax:</strong> K${totalTax.toLocaleString()}</p>
                 <p><strong>Net Salary:</strong> <span class="text-green-600 font-bold">K${net.toLocaleString()}</span></p>`;
        out.innerHTML = html;
      }, 3000);
    }

    function estimateGrossFromNet() {
      const targetNet = parseFloat(document.getElementById("netInput").value);
      const out = document.getElementById("netResult");
      if (isNaN(targetNet) || targetNet <= 0) {
        out.innerHTML = `<p class="text-red-500">⚠️ Enter a valid desired net salary.</p>`;
        return;
      }
      showLoading(out);
      setTimeout(() => {
        // Newton–Raphson
        let gross = targetNet * 1.3 || 1000;  // initial guess
        for (let i = 0; i < 20; i++) {
          const { totalTax } = calculateTax(gross);
          const net = gross - totalTax;
          const error = net - targetNet;
          if (Math.abs(error) < 1) break;
          const fprime = 1 - marginalRate(gross);
          gross = gross - error / fprime;
        }
        gross = Math.max(0, Math.round(gross));
        const { totalTax, breakdown } = calculateTax(gross);
        const actualNet = gross - totalTax;

        let html = `<p><strong>Estimated Gross Salary:</strong> K${gross.toLocaleString()}</p>
                    <p><strong>Target Net:</strong> K${targetNet.toLocaleString()}</p>
                    <p><strong>Actual Net:</strong> K${actualNet.toLocaleString()}</p>
                    <p><strong>Total Tax:</strong> K${totalTax.toLocaleString()}</p>
                    <hr class="my-2">`;
        html += breakdown.map(b =>
            `<p><strong>${b.range}</strong> @ ${b.rate} = <span class="text-blue-700">K${b.tax.toLocaleString()}</span></p>`
          ).join("");

        out.innerHTML = html;
      }, 3000);
    }
  </script>
</body>
</html>

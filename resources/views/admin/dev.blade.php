<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            SQL Query Executor
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6">

                <h3 class="text-xl font-bold mb-2">Enter SQL Query</h3>

                <textarea id="sql-query" class="w-full border px-4 py-2 rounded" rows="3" placeholder="Enter SELECT SQL query"></textarea>

                <div class="mt-2 flex gap-2">
                    <button id="execute-sql" class="bg-blue-500 text-white px-4 py-2 rounded">Run Query</button>
                    <button id="clear-results" class="bg-gray-500 text-white px-4 py-2 rounded">Clear</button>
                    <a href="{{ route('dashboard') }}" class="bg-gray-500 text-white px-4 py-2 rounded">Back to Dashboard</a>
                </div>

                <div id="sql-error" class="text-red-500 mt-2"></div>

                <div class="mt-4">
                    <h4 class="text-lg font-bold">Results:</h4>
                    <table id="sql-results" class="w-full border-collapse border border-gray-500 mt-2">
                        <thead id="sql-results-header"></thead>
                        <tbody id="sql-results-body"></tbody>
                    </table>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.getElementById("execute-sql").addEventListener("click", function () {
            let sqlQuery = document.getElementById("sql-query").value;
            let resultsTable = document.getElementById("sql-results");
            let headerRow = document.getElementById("sql-results-header");
            let bodyRow = document.getElementById("sql-results-body");
            let errorDiv = document.getElementById("sql-error");

            errorDiv.textContent = "";
            headerRow.innerHTML = "";
            bodyRow.innerHTML = "";

            fetch("{{ route('admin.dev.execute') }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ sql: sqlQuery })
            })
                .then(response => response.json())
                .then(data => {
                    if (data.error) {
                        errorDiv.textContent = data.error;
                    } else if (data.data.length === 0) {
                        errorDiv.textContent = "No results found.";
                    } else {
                        let columns = Object.keys(data.data[0]);
                        let headerHTML = "<tr class='bg-gray-200'>";
                        columns.forEach(col => {
                            headerHTML += `<th class="border border-gray-500 px-4 py-2">${col}</th>`;
                        });
                        headerHTML += "</tr>";
                        headerRow.innerHTML = headerHTML;

                        let bodyHTML = "";
                        data.data.forEach(row => {
                            bodyHTML += "<tr class='border border-gray-500'>";
                            columns.forEach(col => {
                                bodyHTML += `<td class="border border-gray-500 px-4 py-2">${row[col]}</td>`;
                            });
                            bodyHTML += "</tr>";
                        });
                        bodyRow.innerHTML = bodyHTML;
                    }
                })
                .catch(error => {
                    errorDiv.textContent = "Failed to execute query.";
                });
        });

        document.getElementById("clear-results").addEventListener("click", function () {
            document.getElementById("sql-results-header").innerHTML = "";
            document.getElementById("sql-results-body").innerHTML = "";
            document.getElementById("sql-error").textContent = "";
            document.getElementById("sql-query").value = "";
        });
    </script>
</x-app-layout>

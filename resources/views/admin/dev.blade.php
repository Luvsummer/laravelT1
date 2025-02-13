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

                <div class="mt-2 flex gap-2">
                    <button id="export-csv" class="bg-green-500 text-white px-4 py-2 rounded">Export CSV</button>
                    <button id="export-json" class="bg-gray-500 text-white px-4 py-2 rounded">Export JSON</button>
                </div>

                <div id="sql-error" class="text-red-500 mt-2"></div>

                <div class="mt-4">
                    <h4 class="text-lg font-bold">Results:</h4>
                    <table id="sql-results" class="w-full border-collapse border border-gray-500 mt-2">
                        <thead id="sql-results-header"></thead>
                        <tbody id="sql-results-body"></tbody>
                    </table>

                    <!-- 分页按钮 -->
                    <div class="mt-4 flex gap-2">
                        <button id="prev-page" class="bg-gray-500 text-white px-4 py-2 rounded">Previous</button>
                        <span id="current-page" class="px-4 py-2">Page 1 of 1</span>
                        <button id="next-page" class="bg-gray-500 text-white px-4 py-2 rounded">Next</button>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        let currentPage = 1;
        let totalPages = 1;
        let perPage = 10; // 每页默认10条

        function fetchData(page = 1) {
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
                body: JSON.stringify({ sql: sqlQuery, page: page })
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

                        // **更新分页信息**
                        currentPage = data.current_page;
                        perPage = data.per_page;
                        totalPages = Math.ceil(data.total / perPage);

                        document.getElementById("current-page").textContent = `Page ${currentPage} of ${totalPages}`;
                        document.getElementById("prev-page").disabled = (currentPage === 1);
                        document.getElementById("next-page").disabled = (currentPage >= totalPages);
                    }
                })
                .catch(error => {
                    errorDiv.textContent = "Failed to execute query.";
                });
        }

        // **分页按钮**
        document.getElementById("execute-sql").addEventListener("click", function () {
            fetchData(1);
        });

        document.getElementById("prev-page").addEventListener("click", function () {
            if (currentPage > 1) {
                fetchData(currentPage - 1);
            }
        });

        document.getElementById("next-page").addEventListener("click", function () {
            if (currentPage < totalPages) {
                fetchData(currentPage + 1);
            }
        });

        document.getElementById("export-csv").addEventListener("click", function () {
            exportData("{{ route('admin.dev.export-csv') }}");
        });

        document.getElementById("export-json").addEventListener("click", function () {
            exportData("{{ route('admin.dev.export-json') }}");
        });

        function exportData(url) {
            let sql = document.getElementById("sql-query").value;
            let form = document.createElement("form");
            form.method = "POST";
            form.action = url;

            let csrfInput = document.createElement("input");
            csrfInput.type = "hidden";
            csrfInput.name = "_token";
            csrfInput.value = "{{ csrf_token() }}";

            let sqlInput = document.createElement("input");
            sqlInput.type = "hidden";
            sqlInput.name = "sql";
            sqlInput.value = sql;

            let pageInput = document.createElement("input");
            pageInput.type = "hidden";
            pageInput.name = "page";
            pageInput.value = currentPage;

            let perPageInput = document.createElement("input");
            perPageInput.type = "hidden";
            perPageInput.name = "per_page";
            perPageInput.value = perPage;

            form.appendChild(csrfInput);
            form.appendChild(sqlInput);
            form.appendChild(pageInput);
            form.appendChild(perPageInput);
            document.body.appendChild(form);
            form.submit();
        }

        document.getElementById("clear-results").addEventListener("click", function () {
            document.getElementById("sql-results-header").innerHTML = "";
            document.getElementById("sql-results-body").innerHTML = "";
            document.getElementById("sql-error").textContent = "";
            document.getElementById("sql-query").value = "";
            currentPage = 1;
            totalPages = 1;
            document.getElementById("current-page").textContent = "Page 1 of 1";
        });
    </script>
</x-app-layout>

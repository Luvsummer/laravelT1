<form method="POST" id="sql-form">
    @csrf
    <textarea name="sql" id="sql" rows="5"></textarea>
    <button type="submit">Execute</button>
    <button id="export-excel">Export Excel</button>
    <button id="export-json">Export JSON</button>
</form>
<div id="result"></div>

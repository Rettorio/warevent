<!-- src/App.svelte -->
<script>
    let data = "";

    async function fetchData() {
        const response = await fetch('/api/data'); // Use relative path
        const result = await response.json();
        data = result.message;
    }
</script>


<main>
    <h1>Svelte on top of Lumen</h1>
    <button on:click={fetchData}>Fetch Data</button>
    {#if data}
        <p>{data}</p>
    {/if}
</main>

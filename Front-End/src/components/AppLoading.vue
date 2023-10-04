<script>
export default {
    data() {
        return {
            name: 'AppLoading'
        }
    },
}
</script>
<template>
    <div class="loading-spinner">
        <lord-icon
            src="https://cdn.lordicon.com/kxoxiwrf.json"
            trigger="loop"
            colors="primary:#d77d85,secondary:#000000"
            stroke="55"
            style="width:250px;height:250px">
        </lord-icon>
    </div>
</template>
<style lang="scss">
    @use '../styles/generals.scss' as *;

    .loading-spinner{
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
    }
</style>
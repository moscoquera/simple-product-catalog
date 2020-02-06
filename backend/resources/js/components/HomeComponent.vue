// HomeComponent.vue

<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-header">Home</div>

                <div class="card-body">
                    Click Categories or Products at top
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "HomeComponent",
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>



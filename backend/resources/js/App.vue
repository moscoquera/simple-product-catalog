// App.vue

<template>
    <div class="container">
        <div>

            <nav class="navbar navbar-expand-sm bg-dark navbar-dark">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <router-link to="/" class="nav-link">Home</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/categories" class="nav-link">Categories</router-link>
                    </li>
                    <li class="nav-item">
                        <router-link to="/products" class="nav-link">Products</router-link>
                    </li>

                    <li class="nav-item">
                        <a href="#" class="nav-link" @click="logout()">Logout</a>
                    </li>
                </ul>
            </nav><br />

            <transition name="fade">
                <router-view></router-view>
            </transition>
        </div>
    </div>
</template>

<style>
    .fade-enter-active, .fade-leave-active {
        transition: opacity .5s
    }
    .fade-enter, .fade-leave-active {
        opacity: 0
    }
</style>

<script>

    export default{
        methods:{
            logout:function () {
                axios.post('/logout',null,{
                    maxRedirects:0
                }).then(response=> {
                        Location.href='/';
                        location.reload();
                    }
                ).catch(error=>{
                    Location.href='/';
                    location.reload();
                });
            }
        }
    }
</script>

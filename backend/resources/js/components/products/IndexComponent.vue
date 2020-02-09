// IndexComponent.vue

<template>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card card-default">
                <div class="card-header">Products
                    <router-link :to="{ name: 'products_new' }" class="btn btn-primary btn-sm float-right">New Product</router-link>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Category</th>
                            <th>Weight</th>
                            <th>Price in USD</th>
                            <th>Images</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="product in products" :key="product.id">
                            <td>{{ product.id }}</td>
                            <td>{{ product.name }}</td>
                            <td>{{ product.description }}</td>
                            <td>{{ product.category?product.category.name:'' }}</td>
                            <td>{{ product.weight }}</td>
                            <td>{{ product.usd_price }}</td>
                            <td>
                                <img v-for="img in product.images" style="max-height: 40px" :src="img">
                            </td>
                            <td>
                                <router-link :to="{name: 'products_edit', params: { id: product.id }}" class="btn btn-primary">Edit</router-link>
                                <br/>
                                <button class="btn btn-danger" @click.prevent="deleteProduct(product)">Delete</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>

                    <paginate
                        v-model="current_page"
                        :page-count="last_page"
                        :prev-text="'Prev'"
                        :next-text="'Next'"
                        :container-class="'pagination pagination-lg'"
                        :pageClass="'page-item'"
                        :pageLinkClass="'page-link'"
                        :prevClass="'page-item'"
                        :prevLinkClass="'page-link'"
                        :nextClass="'page-item'"
                        :nextLinkClass="'page-link'"
                    >
                    </paginate>

                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "IndexComponent",
        mounted() {

        },
        data() {
            return {
                products: [],
                current_page:1,
                last_page:1,
            }
        },
        created() {
            this.populate();
        },
        methods:{
            deleteProduct(product) {
                if(confirm(`Are you sure about delete "${product.name}"?`)){
                    let uri = '/api/products/'+product.id
                    this.axios.delete(uri).then(response=>{
                        this.products.splice(this.products.indexOf(product), 1);
                    }).catch(error=>{
                        alert('error deleting the product: '+error.message)
                    });
                }
            },
            populate(){
                let url = '/api/products'
                let options={
                    params:{
                        page:this.current_page
                    }
                };

                axios.get(url,options).then(response=>{
                    this.products=response.data.data;
                    this.last_page=response.data.meta.last_page;
                });
            }
        },
        watch:{
            current_page:function () {
                this.populate();
            }
        }
    }


</script>

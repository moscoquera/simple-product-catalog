// IndexComponent.vue

<template>
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card card-default">
                <div class="card-header">Index Component
                    <router-link :to="{ name: 'categories_new' }" class="btn btn-primary btn-sm float-right">New Category</router-link>
                </div>
                <div class="card-body">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Parent Category</th>
                            <th>Actions</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr v-for="category in categories" :key="category.id">
                            <td>{{ category.id }}</td>
                            <td>{{ category.name }}</td>
                            <td><img style="max-height: 40px" :src="category.image"></td>
                            <td>{{ category.parent? category.parent.name:'' }}</td>
                            <td>
                                <router-link :to="{name: 'categories_edit', params: { id: category.id }}" class="btn btn-primary">Edit</router-link>
                                <br/>
                                <button class="btn btn-danger" @click.prevent="deleteCategory(category)">Delete</button>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <paginate
                        v-model="current_page"
                        :page-count="last_page"
                        :prev-text="'Prev'"
                        :next-text="'Next'"
                        :container-class="'pagination'"
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
                categories: [],
                current_page:1,
                last_page:1,
            }
        },
        created() {
            this.populate()
        },
        methods:{
            deleteCategory(category) {
                if(confirm(`Are you sure about delete "${category.name}"?`)){
                    let uri = '/api/categories/'+category.id
                    this.axios.delete(uri).then(response=>{
                        this.categories.splice(this.categories.indexOf(category), 1);
                    }).catch(error=>{
                        alert('error deleting the category: '+error.response.data.message)
                    });
                }
            },
            populate(){
                let url = '/api/categories';
                let options={
                    params:{
                        page:this.current_page
                    }
                };

                axios.get(url,options).then(response=>{
                    this.categories=response.data.data;
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

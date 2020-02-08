<template>
    <CSidebar
        fixed
        :minimize="minimize"
        :show.sync="show"
    >
        <h2>Categories</h2>
        <CRenderFunction flat :content-to-render="this.mapCategories(this.childs)" />
        <CSidebarMinimizer
            class="d-md-down-none"
            @click.native="minimize = !minimize"
        />
    </CSidebar>
</template>

<script>

    export default {
        name: 'TheSidebar',
        props:{
          childs:{Type:Array}
        },
        data () {
            return {
                minimize: false,
                show: 'responsive'
            }
        },
        mounted () {
            this.$root.$on('toggle-sidebar', () => {
                const sidebarOpened = this.show === true || this.show === 'responsive'
                this.show = sidebarOpened ? false : 'responsive'
            })
            this.$root.$on('toggle-sidebar-mobile', () => {
                const sidebarClosed = this.show === 'responsive' || this.show === false
                this.show = sidebarClosed ? true : 'responsive'
            })
        },
        methods:{
            categoryToSideBar(categories){
              var output = [];
              let context=this;
              categories.forEach(function (category) {
                  let categoryLink= {
                      _name:'CSidebarNavItem',
                      name:category.name,
                      to:context.$router.resolve({
                          name:'category',
                          params:{
                              id:category.id
                          }
                      }).href,
                  };
                  if(category.childs && category.childs.length>0){
                      categoryLink.items=context.categoryToSideBar(category.childs)
                      categoryLink._name='CSidebarNavDropdown';
                  }

                  output.push(categoryLink);
              });

              return output;
            },
            mapCategories(childs){
                let _childs=this.categoryToSideBar(childs);
                return [
                    {
                        _name: 'CSidebarNav',
                        _children: _childs
                    }
                ]
            }
        }
    }
</script>

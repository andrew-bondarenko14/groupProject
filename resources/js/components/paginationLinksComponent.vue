<template>
    <div>
        <ul class="pagination_links">
            <li @click="previousPage()"><i class="arrow left"></i></li>
            <li  v-for="n in pagesCount"
                 :class="active(n) ? 'active' : ''"
                 @click="setPage(n)">{{ n }}</li>
            <li @click="nextPage()"><i class="arrow right"></i></li>
        </ul>
    </div>
</template>

<script >
    export default {
        props: ['pagesCount'],
        data: function () {
            return {
               currentPage: 1,
            }
        },
        watch: {
            currentPage: function () {
                if (this.currentPage <= 1)
                    this.currentPage = 1;
                if (this.currentPage > this.pagesCount)
                    this.currentPage = this.pagesCount;
                this.$emit('pageChanged',this.currentPage);
            }
        },
        created() {
            let self = this;
            axios.get('/api/user')
                .then( function (response) {
                    self.name = response.data.name + ' ' + response.data.surname;
                    self.avatar = response.data.avatar
                })
                .catch(function (error) {
                    if(error.response.status)
                        self.$router.push({name : 'authorization'});
                })
        },
        methods: {
            setPage(n) {
                this.currentPage = n;
            },
            active(n) {
                return n == this.currentPage;
            },
            previousPage() {
                this.currentPage--;
            },
            nextPage() {
                this.currentPage++;
            }
        }
    }
</script>

<style scoped>
    .pagination_links {
        list-style: none;
        display: flex;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.05);
        border-radius: 6px;
        border-top: 1px solid #F0F0F0;
        background: #FFFFFF;
        padding: 0;
    }
    .pagination_links li {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 85px;
        cursor: pointer;
        font-style: normal;
        font-weight: bold;
        font-size: 14px;
        line-height: 60px;
        color: #999999;
        border: 1px solid #DADADA;
        border-right: none;
        text-align: center;
    }
    .pagination_links li:last-child {
        border: 1px solid #DADADA;
    }

    .arrow {
        position: relative;
        border: solid #D8D8D8;
        border-width: 0 3px 3px 0;
        display: inline-block;
        padding: 7px;
    }
    .arrow::after {
        content: '';
        position: absolute;
        border: none;
        width: 20px;
        border-top: 3px solid #D8D8D8;
        transform: rotate(-135deg) translate(18%,-43%);
        left: 0;
        top: 50%;
    }

    .left {
        transform: rotate(135deg);
        -webkit-transform: rotate(135deg);
    }
    .right {
        transform: rotate(-45deg);
        -webkit-transform: rotate(-45deg);
    }
    .active {
        background: #FAFAFA;
    }
</style>

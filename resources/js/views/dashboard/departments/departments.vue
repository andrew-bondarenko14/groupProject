<template>
    <div>
        <div v-if="showModal">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container">
                        <div class="modal-custom-header">Подтверждение удаления</div>
                        <div class="modal-custom-body">{{modalQuestion}}</div>
                        <div class="modal-custom-buttons">
                            <button class="button-save" @click="modalWindowDelete">Удалить</button>
                            <button class="button-cancel" @click="modalWindowCancel">Отмена</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div v-if="showModals">
            <div class="modal-mask">
                <div class="modal-wrapper">
                    <div class="modal-container">
                        <div class="modal-custom-body">{{modalQuestion}}</div>
                        <div class="modal-custom-buttons">
                            <button class="button-save" @click="modalWindowCancel">Ок</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid dashboard_departments_header">
            <div class="dashboard_departments">
                <div class="row">
                    <div class="col-12">
                        <span class="dashboard_departments_title">Управление отделами</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid dashboard_departments_wrap">
            <div class="dashboard_departments">
                <div class="row">
                    <div class="col-12 col-md-3 dashboard_new_department_wrap">
                        <div class="row dashboard_new_department_link">
                            <div class="col-12 pr-0">
                                <div class="dashboard_new_department">
                                    <router-link to="/dashboard/department/create">Добавить отдел</router-link>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-9">
                        <div class="row justify-content-end">
                            <div class="col-12 col-md-3">
                                <div class="row dashboard_departments_content">
                                    <div class="col-3  pad">
                                    </div>
                                    <div class="col-3 pad">
                                        <div @click="updateDepartments">
                                            <b-modal v-model="noSelectedDepartments" ok-only>Вы не выбрали отдел для
                                                редактирования
                                            </b-modal>
                                            <b-modal v-model="someSelectedDepartments" ok-only>Вы выбрали более одного
                                                отдела для редактирования
                                            </b-modal>
                                            <img src="/storage/dashboard_icons/users/ic_update.svg" alt="update">
                                        </div>
                                    </div>
                                    <div class="col-3 pad">
                                        <div class="active-link-show-modal" v-on:click="showModalWindow()"><img
                                            src="/storage/dashboard_icons/users/ic_delete.svg" alt="delete"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-10 col-md-9 dashboard_departments_search">
                                <v-text-field
                                    v-model="search"
                                    append-icon="mdi-magnify"
                                    label="Поиск"
                                    single-line
                                    hide-details
                                ></v-text-field>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12 departments_table_wrap">
                                <div class="departments_table">
                                    <v-data-table
                                        :headers="headersToShow"
                                        :items="departmentsToShow"
                                        :page.sync="page"
                                        :items-per-page="itemsPerPage"
                                        :search="search"
                                        hide-default-footer
                                        class="elevation-1"
                                        @page-count="pageCount = $event"
                                        item-key="id"
                                        :single-select="singleSelect"
                                        show-select
                                        v-model="checkedRecordsList"
                                        loading loading-text="Загрузка..."
                                    ></v-data-table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dashboard_departments_wrap mr-3 ml-1">
            <div class="row justify-content-end">
                <div class="col-9 p-0 text-left dashboard_departments_pag mb-2 mt-2">
                    <div class="pagination_wrap">
                        <v-pagination v-model="page"
                                      :length="pageCount"
                                      color="#FAFAFA"
                                      next-icon='mdi-arrow-right'
                                      prev-icon='mdi-arrow-left'
                        ></v-pagination>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "departments",
        data() {
            return {
                departments: [],
                users: [],
                noSelectedDepartments: false,
                singleSelect: false,
                someSelectedDepartments: false,
                checkedRecords: [],
                checkedRecordsList: [],
                showModal: false,
                showModals: false,
                modalQuestion: '',
                search: '',
                pageCount: 0,
                headers: [],
                headersToShow: [],
                page: 1,
                itemsPerPage: 15,
                departmentsToShow: this.departments,
            }
        },
        mounted() {
            this.read();
        },
        watch: {
            departments: function () {

                this.departmentsToShow = this.departments;
            },
        },
        methods: {
            read() {
                this.headers = [];
                axios.get('/api/dashboard/departments')
                    .then(response => {
                        this.departments = response.data;
                        this.departments.forEach(function (department) {
                            let director = {};
                            director['name'] = 'Не установлен';
                            if (!department.director)
                                department.director = director;
                            console.log(department.director);
                        })
                        console.log(this.departments);
                        this.headers.push(
                            {text: 'Название', value: 'name', visible: true},
                            {text: 'Руководитель', value: 'director.name', visible: true},
                            {text: 'Количество сотрудников', value: 'users.length', visible: true},
                        );
                        this.headersToShow = this.headers;
                    });


            },
            showModalWindow() {
                if (this.checkedRecordsList.length > 1) {
                    this.modalQuestion = 'Вы уверены, что хотите удалить данные записи?';
                    this.showModal = true;
                } else if (this.checkedRecordsList.length > 0) {
                    this.modalQuestion = 'Вы уверенны что хотите удалить данную запись?';
                    this.showModal = true;
                } else {
                    this.modalQuestion = 'Пожалуйста выберите запись';
                    this.showModals = true;
                }
            },
            modalWindowDelete() {
                axios.post('/api/dashboard/department/delete', {
                    data: this.checkedRecordsList,
                })
                    .then(response => {
                        if (response.data === true) {
                            alert('Данные успешно удалены');
                            this.checkedRecordsList = [];
                            this.read();
                        }
                    });
                this.showModal = false;
            },
            modalWindowCancel() {
                this.showModal = false;
                this.showModals = false;
            },
            updateDepartments() {
                this.checkedRecords = [];
                if (this.checkedRecordsList.length > 0) {
                    let arr = [];
                    $.each(this.checkedRecordsList, function (key, value) {
                        arr.push(value['id']);
                    });
                    this.checkedRecords = arr;
                }
                if (this.checkedRecords.length == 1) {
                    this.someSelectedDepartments = false;
                    this.noSelectedDepartments = false;
                    this.$router.push({name: 'departmentsEdit', params: {id: this.checkedRecords[0]}});
                }
                if (this.checkedRecords.length == 0) {
                    this.noSelectedDepartments = true;
                } else {
                    this.someSelectedDepartments = true;
                }
            },
        }
    }
</script>

<style scoped>
    .dashboard_departments {
        background-color: #fff;
    }

    .dashboard_departments_header {
        padding: 10px 15px 8px 15px;
    }

    .dashboard_departments_wrap {
        padding: 0 15px;
    }

    .dashboard_departments_title {
        font-weight: 300;
        font-size: 36px;
        color: #808080;
        padding-left: 14px;
        line-height: 90px;
    }

    .departments_table thead th, .departments_table tbody th {
        font-family: Roboto;
        font-weight: 500;
    }

    .departments_table thead th {
        font-size: 13px;
        color: #666;
    }

    .departments_table thead tr, .departments_table tbody th {
        line-height: 55px;
    }

    .dashboard_new_department {
        display: flex;
        justify-content: center;
        padding: 0 22px 0 22px;
    }

    .dashboard_new_department_wrap {
        border-right: 2px solid #F5F5F5;
    }

    .dashboard_new_department_link {
        border-bottom: 2px solid #F5F5F5;
    }

    .dashboard_new_department a {
        display: block;
        height: 48px;
        background-color: #1875F0;
        border-radius: 5px;
        width: 100%;
        color: #fff;
        line-height: 48px;
        text-align: center;
        text-transform: uppercase;
        opacity: 0.9;
        margin-bottom: 12px;
    }

    .dashboard_new_department a:hover {
        text-decoration: none;
        opacity: 1;
    }

    .dashboard_departments_pag {
        background-color: #fff;
    }

    .dashboard_departments_content {
        margin-bottom: 6px;
        border-bottom: 1px solid #F5F5F5;
    }

    .dashboard_departments_content img {
        opacity: 0.8;
    }

    .dashboard_departments_content img:hover {
        opacity: 1;
        cursor: pointer;
    }

    .pagination_wrap /deep/ button {
        margin: 0;
        height: 60px;
        width: 70px;
    }

    .v-pagination {
        align-items: center;
        display: inline-flex;
        list-style-type: none;
        justify-content: left;
        margin: 0;
        max-width: 100%;
        width: 100%;
    }

    .pagination_wrap /deep/ button:hover {
        background-color: #FAFAFA;
    }

    .pagination_wrap /deep/ .v-pagination__item--active {
        color: #999999;
    }

    .pagination_wrap /deep/ .v-pagination__item--active {
        box-shadow: 0 3px 1px -2px rgba(0, 0, 0, .2), 0 2px 2px 0 rgba(0, 0, 0, .14), 0 1px 5px 0 rgba(0, 0, 0, .12);
    }

    .departments_table_wrap {
        padding: 0;
        padding-right: 15px;
    }

    .departments_table_wrap /deep/ tr > th:first-child .v-data-table__checkbox {
        position: absolute;
        top: -55px;
    }

    .departments_table /deep/ .v-icon.v-icon {
        font-size: 20px;
    }

    .departments_table /deep/ .theme--light.v-icon {
        color: #E6E6E6;
    }

    .departments_table /deep/ .v-data-table__selected .theme--light.v-icon {
        color: #1875F0;
    }

    .departments_table /deep/ .theme--light.v-data-table tbody tr:hover:not(.v-data-table__expanded__content):not(.v-data-table__empty-wrapper) {
        background: #fff;
    }

    .departments_table /deep/ .theme--light.v-data-table tbody tr.v-data-table__selected {
        background: #fff;
    }

    @media (max-width: 768px) {
        .dashboard_departments_wrap /deep/ tr > th:first-child .v-data-table__checkbox {
            top: -127px;
        }

        .dashboard_departments_content {
            margin-bottom: 6px;
            border-bottom: 1px solid #F5F5F5;
            justify-content: space-evenly;
        }

        .departments_table_wrap[data-v-ef82936e] tr > th:first-child .v-data-table__checkbox {
            position: absolute;
            top: -55px;
            left: 70px;
        }

        .dashboard_departments_pag {
            background-color: #fff0;
            padding: 0;
            padding-right: 15px;
            padding-left: 15px;
        }

        .pad{
            margin-left: 10px;
        }

        .dashboard_departments_search {
            margin-right: 15px;
            padding-left: 0;
        }

        .departments_table_wrap {
            padding: 0;
            padding-right: 15px;
            padding-left: 15px;
        }
    }


    /*modal styles*/
    .active-link-show-modal {
        cursor: pointer;
    }

    .modal-mask {
        position: fixed;
        z-index: 9998;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: table;
        transition: opacity 0.3s ease;
    }

    .modal-wrapper {
        display: table-cell;
        vertical-align: middle;
    }

    .modal-container {
        font-family: Roboto;
        font-style: normal;
        font-weight: 500;
        width: 440px;
        margin: 0px auto;
        padding: 40px;
        background-color: #FFF;
        box-shadow: 0px 5px 30px rgba(0, 0, 0, 0.07);
        border-radius: 6px;
        transition: all 0.3s ease;
    }

    .modal-custom-header {
        font-size: 18px;
        line-height: 30px;
        color: #666666;
    }

    .modal-custom-body {
        margin: 35px 0 55px;
        font-size: 14px;
        line-height: 20px;
        color: #808080;
    }

    .modal-mask .modal-wrapper .modal-container .modal-custom-buttons button {
        min-width: 120px;
        font-weight: 900;
        font-size: 12px;
        line-height: 50px;
        text-transform: uppercase;
    }

    .modal-custom-buttons .button-save {
        margin: 0 10px 0 0;
        color: #FFFFFF;
        background-color: #1875F0;
        border: 2px;
    }

    .modal-custom-buttons .button-save:hover {
        background: #1855F0;
        transition: 0.5s;
    }

    .modal-custom-buttons .button-cancel {
        margin: 0;
        color: #B2B2B2;
        background-color: #FFFFFF;
        border: 2px solid #F5F5F5;
    }

    .modal-custom-buttons .button-cancel:hover {
        color: #FFFFFF;
        background: #B2B2B2;
        transition: 0.5s;
    }

    @media screen and (max-width: 480px) {
        .modal-container {
            width: 240px;
        }
    }

    /*end modals styles*/

</style>

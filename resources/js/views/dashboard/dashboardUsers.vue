<template>
    <div>
        <div class="container-fluid dashboard_users_header">
            <div class="dashboard_users">
                <div class="row">
                    <div class="col-12">
                        <span class="dashboard_users_title">Управление пользователями</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid dashboard_users_wrap">
            <div class="dashboard_users">
                <div class="row">
                    <div class="col-12">
                        <div class="container-fluid">
                            <div class="row">
                                <div class="col-12 col-md-3 dashboard_new_user_wrap">
                                    <div class="row dashboard_new_user_link">
                                        <div class="col-12 pr-0">
                                            <div class="dashboard_new_user">
                                                <router-link to="/dashboard/user/create">Добавить пользователя</router-link>
                                            </div>
                                            <ul class="departments_list">
                                                <li @click="showUsers('all')"
                                                    :class="selectedDepartment == 'all' ? 'active' : '' ">Все пользователи
                                                </li>
                                                <li v-for="department in departmentsList"
                                                    :class="selectedDepartment == department.id ? 'active' : ''"
                                                    @click="showUsers(department.id)">
                                                    {{ department.name }}
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-9">
                                    <div class="row justify-content-end">
                                        <div class="col-12 col-md-3">
                                            <div class="row dashboard_users_content">
                                                <div class="col-3">
                                                </div>
                                                <div class="col-3">
                                                    <div v-if="this.checkedRecords.length !== 0">
                                                        <div @click="updateUser">
                                                            <b-modal v-model="someSelectedUsers" centered ok-only>
                                                                <p style="margin-left: 10px;">Вы выбрали более одного
                                                                    пользователя для редактирования</p>
                                                            </b-modal>
                                                            <img src="/storage/dashboard_icons/users/ic_update.svg" alt="update">
                                                        </div>
                                                    </div>
                                                    <div v-else class="img_disabled">
                                                        <img src="/storage/dashboard_icons/users/ic_update.svg" alt="update">
                                                    </div>
                                                </div>
                                                <div class="col-3">
                                                    <div v-if="this.checkedRecords.length !== 0">
                                                        <div @click="deleteUsersCheck">
                                                            <img src="/storage/dashboard_icons/users/ic_delete.svg" alt="delete">
                                                        </div>
                                                    </div>
                                                    <div v-else class="img_disabled">
                                                        <img src="/storage/dashboard_icons/users/ic_delete.svg" alt="delete">
                                                    </div>
                                                    <b-modal v-model="oneDeletedUsers" hide-footer centered title="Подтверждение удаления">
                                                        <div class="d-block users_delete_modal">
                                                            <p>Вы уверены, что хотите удалить данную запись?</p>
                                                            <button @click="deleteUsers" class="btn_delete_ok">удалить</button>
                                                            <button @click="oneDeletedUsers = false" class="btn_delete_cancel">отмена</button>
                                                        </div>
                                                    </b-modal>
                                                    <b-modal v-model="someDeletedUsers" hide-footer centered title="Подтверждение удаления">
                                                        <div class="d-block users_delete_modal">
                                                            <p>Вы уверены, что хотите удалить данные записи?</p>
                                                            <button @click="deleteUsers" class="btn_delete_ok">удалить</button>
                                                            <button @click="someDeletedUsers = false" class="btn_delete_cancel">отмена</button>
                                                        </div>
                                                    </b-modal>
                                                </div>
                                                <div @click="showSettings=true" class="col-3"><img
                                                    src="/storage/dashboard_icons/users/ic_settings.svg" alt="settings"></div>
                                            </div>
                                        </div>
                                        <div class="col-10 col-md-9 dashboard_users_search">
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
                                        <div class="col-12 users_table_wrap">
                                            <div class="users_table">
                                                <v-data-table
                                                    :headers="headersToShow"
                                                    :items="usersToShow"
                                                    :page.sync="page"
                                                    :items-per-page="itemsPerPage"
                                                    :search="search"
                                                    hide-default-footer
                                                    class="elevation-1"
                                                    @page-count="pageCount = $event"
                                                    item-key="id"
                                                    show-select
                                                    locale="ru-RU"
                                                    v-model="checkedRecords"
                                                    loading loading-text="Загрузка..."
                                                    mobile-breakpoint="768px"
                                                    color="#f00"
                                                >
                                                    <!--<template>
                                                        <tr>
                                                            <td>
                                                                <v-select
                                                                    :items="this.users.accesses"
                                                                    label="Select One"
                                                                ></v-select>
                                                            </td>
                                                        </tr>
                                                    </template>-->
                                                </v-data-table>
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="text-left">
                                                <div class="pagination_wrap">
                                                    <v-pagination
                                                        v-model="page"
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <table-settings @close="showSettings=false" @saveSettings="saveTableSettings($event)" :columnsToShow="headers"
                        :opened="showSettings"></table-settings>
    </div>
</template>

<script>
    export default {
        name: "dashboardUsers",
        data() {
            return {
                users: [],
                checkedRecords: [],
                someSelectedUsers: false,
                selectedDepartment: 'all',
                showSettings: false,
                page: 1,
                pageCount: 0,
                itemsPerPage: 15,
                selected: [],
                singleSelect: false,
                search: '',
                headers: [],
                headersToShow: [],
                departmentsList: [],
                usersToShow: this.users,
                checkedRecordsDelete: [],
                /*userAccesses: [
                    {
                        id: 0,
                        name: 'Пользователь'
                    },
                    {
                        id: 1,
                        name: 'Администратор'
                    }
                ],*/
                roles: [
                    {
                        id: 0,
                        name: 'Пользователь'
                    },
                    {
                        id: 1,
                        name: 'Администратор'
                    }
                ],
                oneDeletedUsers: false,
                someDeletedUsers: false,
            }
        },
        created() {
            this.read();
            this.getDepartments();
        },
        watch: {
            users: function () {
                this.usersToShow = this.users;
            },
        },
        methods: {
            async read() {
                this.users = [];
                await axios.get('/api/dashboard/users')
                    .then(response => (this.users = response.data));
                for (let userItem in this.users) {
                    if (this.users[userItem].is_admin == 0) {
                        this.users[userItem].is_admin = this.roles[0].name;
                    } else {
                        this.users[userItem].is_admin = this.roles[1].name;
                    }
                    //this.users[userItem].accesses = this.userAccesses;
                }
                this.headers = [];
                this.headers.push(
                    {text: 'id', value: 'id', visible: false},
                    {text: 'Фамилия', value: 'surname', visible: true},
                    {text: 'Имя', value: 'name', visible: true},
                    {text: 'Отчество', value: 'middle_name', visible: true},
                    {text: 'Email', value: 'email', visible: true},
                    {text: 'Должность', value: 'position', visible: true},
                    {text: 'Отдел', value: 'department_id', visible: false},
                    {text: 'Дата рождения', value: 'birthday', visible: false},
                    {text: 'Доступные модули', value: 'accesses', visible: false},
                    {text: 'Роль в системе', value: 'is_admin', visible: false},
                );
                this.headersToShow = this.headers.filter(header => header.visible);
            },
            updateUser() {
                if (this.checkedRecords.length == 1) {
                    this.someSelectedUsers = false;
                    this.$router.push({name: 'editUser', params: {id: this.checkedRecords[0].id}});
                } else {
                    this.someSelectedUsers = true;
                }
            },
            async getDepartments() {
                await axios.get('/api/dashboard/departments')
                    .then(response => (this.departmentsList = response.data));
                _.forEach(this.users, (user, key) => {
                    this.users[key].department_id = _.find(this.departmentsList, { 'id': this.users[key].department_id }).name
                });
            },
            showUsers(id) {
                this.selectedDepartment = id;
                if (id == 'all')
                    this.usersToShow = this.users.filter(item => item.department_id != null);
                else
                    this.usersToShow = this.users.filter(item => item.department_id == id);
                this.checkedRecords = [];
            },
            saveTableSettings(event) {
                this.showSettings = false;
                this.headersToShow = event;
                this.$forceUpdate();
            },
            async deleteUsers() {
                for (let checked in this.checkedRecords) {
                    this.checkedRecordsDelete.push(this.checkedRecords[checked].id);
                }
                await axios.delete('/api/dashboard/users', {
                    data: {
                        users: this.checkedRecordsDelete
                    }
                })
                .then(function(response) {
                    if (response.result === false) {
                        alert(response.message)
                    }
                });
                this.oneDeletedUsers = false;
                this.someDeletedUsers = false;
                this.checkedRecords = [];
                this.read();
            },
            deleteUsersCheck() {
                if (this.checkedRecords.length == 1) {
                    this.oneDeletedUsers = true;
                } else {
                    this.someDeletedUsers = true;
                }
            }
        }
    }
</script>

<style scoped>
    .dashboard_users {
        background-color: #fff;
    }
    .dashboard_users_header {
        padding: 10px 15px 8px 15px;
    }
    .dashboard_users_wrap {
        padding: 0 15px;
    }
    .dashboard_users_title {
        font-weight: 300;
        font-size: 36px;
        color: #808080;
        padding-left: 14px;
        line-height: 90px;
    }
    .users_table thead th, .users_table tbody th {
        font-family: Roboto;
        font-weight: 500;
    }
    .users_table thead th {
        font-size: 13px;
        color: #666;
    }
    .users_table thead tr, .users_table tbody th {
        line-height: 55px;
    }
    .dashboard_new_user {
        display: flex;
        justify-content: center;
        padding: 0 22px 0 22px;
    }
    .dashboard_new_user_wrap {
        border-right: 2px solid #F5F5F5;
    }
    .dashboard_new_user_link {
        border-bottom: 2px solid #F5F5F5;
    }
    .dashboard_new_user a {
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
    .dashboard_new_user a:hover {
        text-decoration: none;
        opacity: 1;
    }
    .dashboard_users_content {
        margin-bottom: 6px;
        border-bottom: 1px solid #F5F5F5;
    }
    .dashboard_users_content img {
        opacity: 0.8;
    }
    .dashboard_users_content img:hover {
        opacity: 1;
        cursor: pointer;
    }
    .departments_list {
        list-style: none;
        width: 100%;
        overflow-y: auto;
        max-height: 600px;
    }
    .departments_list li {
        height: 55px;
        border-top: 1px solid #F5F5F5;
        border-bottom: 1px solid #F5F5F5;
        display: flex;
        align-items: center;
        justify-content: center;
        font-style: normal;
        font-weight: 500;
        font-size: 13px;
        line-height: 30px;
        color: #999999;
        cursor: pointer;
        border-left: 2px solid transparent;

    }
    .departments_list .active {
        background: #FCFCFC;
        border-left: 2px solid #1875F0;;
    }
    .v-pagination {
        justify-content: left;
    }
    .pagination_wrap /deep/ button {
        margin: 0;
        height: 60px;
        width: 70px;
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
    .dashboard_users_wrap /deep/ label {
        margin-top: 0;
    }
    table /deep/ .theme--light.v-icon {
        color: #f00;
    }
    .img_disabled img:hover {
        cursor: default;
        opacity: 0.9;
    }
    .dashboard_users_wrap /deep/ tr > th:first-child .v-data-table__checkbox {
        position: absolute;
        top: -55px;
    }
    .users_table /deep/ .v-icon.v-icon {
        font-size: 20px;
    }
    .users_table /deep/ .theme--light.v-icon {
        color: #E6E6E6;
    }
    .users_table /deep/ .v-data-table__selected .theme--light.v-icon {
        color: #1875F0;
    }
    .users_table /deep/ .theme--light.v-data-table tbody tr:hover:not(.v-data-table__expanded__content):not(.v-data-table__empty-wrapper) {
        background: #fff;
    }
    .users_table /deep/ .theme--light.v-data-table tbody tr.v-data-table__selected {
        background: #fff;
    }
    .btn_delete_ok, .btn_delete_cancel {
        width: 120px;
        height: 50px;
        line-height: 50px;
        text-align: center;
        border-radius: 5px;
        text-transform: uppercase;
        margin-top: 30px;
    }
    .btn_delete_ok:focus, .btn_delete_cancel:focus {
        outline: none;
    }
    .btn_delete_ok {
        background-color: #1875F0;
        color: #fff;
        border: none;
    }
    .btn_delete_cancel {
        background-color: #fff;
        border: 2px solid #F5F5F5;
        color: #B3B3B3;
    }
    .users_delete_modal p {
        font-weight: 500;
        font-size: 14px;
        color: #808080;
        margin-top: 20px;
    }
    .dashboard_users_wrap /deep/ .pagination_wrap .v-pagination__item--active {
        outline: none;
    }
    @media (max-width: 768px) {
        .dashboard_users_wrap /deep/ tr > th:first-child .v-data-table__checkbox {
            top: -127px;
        }
        .dashboard_users_search {
            margin-right: 15px;
            padding-left: 0;
        }
    }
</style>

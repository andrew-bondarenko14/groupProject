<template>
    <div>
        <div class="container-fluid dashboard_users_header">
            <div class="dashboard_users">
                <div class="row">
                    <div class="col-12">
                        <span class="dashboard_users_title">Создание пользователя</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid dashboard_users_wrap">
            <div class="dashboard_users">
                <div class="row">
                    <div class="col-12">
                        <div class="container">
                            <div class="row">
                                <div class="col-12">
                                    <div class="edit_user_header">Общая информация</div>
                                </div>
                                <div class="col-12 col-md-6">
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="edit_user_form edit_user_header_form">
                                        <div>
                                            <v-menu
                                                v-model="menu"
                                                :close-on-content-click="false"
                                                :nudge-right="40"
                                                transition="scale-transition"
                                                offset-y
                                                min-width="290px"
                                            >
                                                <template v-slot:activator="{ on }">
                                                    <v-text-field
                                                        v-model="user.birthday"
                                                        label="Дата рождения:"
                                                        append-icon="mdi-calendar"
                                                        readonly
                                                        :error-messages="errors.birthday"
                                                        v-on="on"
                                                    ></v-text-field>
                                                </template>
                                                <v-date-picker v-model="user.birthday" @input="menu = false" color="#1875F0" locale="ru-RU"></v-date-picker>
                                            </v-menu>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="edit_user_form edit_user_left_column">
                                        <div>
                                            <v-text-field
                                                v-model="user.surname"
                                                label="Фамилия:"
                                                :error-messages="errors.surname"
                                            ></v-text-field>
                                        </div>
                                        <div>
                                            <v-text-field
                                                v-model="user.name"
                                                label="Имя:"
                                                :error-messages="errors.name"
                                            ></v-text-field>
                                        </div>
                                        <div>
                                            <v-text-field
                                                v-model="user.middle_name"
                                                label="Отчество:"
                                                :error-messages="errors.middle_name"
                                            ></v-text-field>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6">
                                    <div class="edit_user_form edit_user_right_column">
                                        <div>
                                            <v-select
                                                v-model="user.department_id"
                                                :items="departments"
                                                item-text="name"
                                                item-value="id"
                                                label="Отдел:"
                                            ></v-select>
                                        </div>
                                        <div>
                                            <v-text-field
                                                v-model="user.position"
                                                label="Должность:"
                                                :error-messages="errors.position"
                                            ></v-text-field>
                                        </div>
                                        <div>
                                            <v-menu
                                                v-model="menu1"
                                                :close-on-content-click="false"
                                                :nudge-right="40"
                                                transition="scale-transition"
                                                offset-y
                                                :error-messages="errors.hired_at"
                                                min-width="290px"
                                            >
                                                <template v-slot:activator="{ on }">
                                                    <v-text-field
                                                        v-model="user.hired_at"
                                                        label="Дата начала работы:"
                                                        readonly
                                                        append-icon="mdi-calendar"
                                                        v-on="on"
                                                    ></v-text-field>
                                                </template>
                                                <v-date-picker v-model="user.hired_at" @input="menu1 = false" color="#1875F0" locale="ru-RU"></v-date-picker>
                                            </v-menu>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-6 edit_user_admin_success">
                                    <div class="admin_success_title">Доступ администратора:</div>
                                </div>
                                <div class="col-12 col-md-6 edit_user_admin_success edit_user_right_column">
                                    <v-row justify="space-around">
                                        <v-checkbox v-model="isAdminTrue" class="mx-4" color="#1875F0" label="Да" @click="adminTrue"></v-checkbox>
                                        <v-checkbox v-model="isAdminFalse" class="mx-4" color="#1875F0" label="Нет" @click="adminFalse"></v-checkbox>
                                    </v-row>
                                </div>
                                <div class="col-12">
                                    <div class="edit_user_header">Контактная информация</div>
                                </div>
                                <div class="col-md-6">
                                    <div class="
                                    edit_user_form
                                    edit_user_left_column
                                    edit_user_contacts">
                                        <div>
                                            <v-text-field
                                                v-model="user.email"
                                                label="E-mail:"
                                                :error-messages="errors.email"
                                            ></v-text-field>
                                        </div>
                                        <div>
                                            <v-text-field
                                                v-model="user.skype_login"
                                                label="Skype:"
                                                :error-messages="errors.skype_login"
                                            ></v-text-field>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="
                                    edit_user_form
                                    edit_user_right_column
                                    edit_user_contacts">
                                        <div>
                                            <v-text-field
                                                v-model="user.mobile_phone"
                                                label="Мобильный телефон:"
                                                :error-messages="errors.mobile_phone"
                                                type="number"
                                            ></v-text-field>
                                        </div>
                                        <div>
                                            <v-text-field
                                                v-model="user.work_phone"
                                                label="Рабочий телефон:"
                                                :error-messages="errors.work_phone"
                                                type="number"
                                            ></v-text-field>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12">
                                    <div class="edit_user_buttons">
                                        <button class="edit_user_btn_save" v-on:click="createUser">
                                            Сохранить
                                            <b-modal v-model="userCreated" centered ok-only @ok="userOk">Пользователь успешно создан</b-modal>
                                        </button>
                                        <router-link to="/dashboard/users" class="edit_user_link_cancel">Отмена</router-link>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "createUser",
        data() {
            return {
                user: {},
                errors: [],
                menu: false,
                menu1: false,
                isAdminTrue: false,
                isAdminFalse: false,
                initials: '',
                userCreated: false,
                departments: []
            }
        },
        mounted() {
            this.getDepartments();
        },
        methods: {
            async createUser() {
                this.userCreate = false;
                this.errors = [];
                await axios.post('/api/dashboard/user', this.user)
                    .catch(error => {
                        if (error.response.status == 422) {
                            this.errors = error.response.data.errors;
                        }
                    });
                if (this.errors.length === 0) {
                    this.userCreated = true;
                } else {
                    this.userCreated = false;
                }
            },
            getDepartments() {
                axios.get('/api/dashboard/departments')
                    .then(response => (this.departments = response.data));
            },
            adminTrue() {
                if (this.isAdminTrue === false) {
                    this.user.is_admin = 1;
                    this.isAdminTrue = true;
                    this.isAdminFalse = false;
                }
                if (this.isAdminTrue === true) {
                    this.user.is_admin = 1;
                    this.isAdminTrue = true;
                    this.isAdminFalse = false;
                }
            },
            adminFalse() {
                if (this.isAdminFalse === false) {
                    this.user.is_admin = 0;
                    this.isAdminTrue = false;
                    this.isAdminFalse = true;
                }
                if (this.isAdminFalse === false) {
                    this.user.is_admin = 0;
                    this.isAdminTrue = false;
                    this.isAdminFalse = true;
                }
            },
            userOk() {
                this.$router.push({name: 'dashboardUsers'});
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
    .dashboard_users_title {
        font-weight: 300;
        font-size: 36px;
        color: #808080;
        padding-left: 14px;
        line-height: 90px;
    }
    .dashboard_users_wrap {
        padding: 0 15px;
    }
    .edit_user_header {
        text-align: center;
        line-height: 90px;
        color: #999;
        font-weight: 500;
        font-size: 18px;
        border-bottom: 2px solid #F5F5F5;
    }
    .edit_user_avatar img, .edit_user_initials{
        width: 133px;
        height: 133px;
        border: 1px solid #666;
        border-radius: 50%;
        margin-left: 30%;
    }
    .edit_user_initials > div {
        text-align: center;
        line-height: 133px;
        font-size: 32px;
        color: #999;
    }
    .edit_user_form, .edit_user_form input,
    .edit_user_admin_success {
        font-weight: 500;
        font-size: 14px;
        color: #666;
    }
    .edit_user_form input {
        margin: 10px 0 17px 0;
        line-height: 60px;
        width: 80%;
        border: 2px solid #F5F5F5;
        border-radius: 4px;
    }
    .edit_user_form span {
        line-height: 30px;
    }
    .edit_user_header_form {
        margin-top: 30px;
    }
    .edit_user_left_column,
    .edit_user_admin_success > .admin_success_title {
        padding-left: 75px;
        padding-right: 25px;
    }
    .edit_user_right_column, .edit_user_header_form {
        padding-right: 75px;
        padding-left: 25px;
    }
    .edit_user_admin_success > .admin_success_title {
        margin-top: 20px;
    }
    .edit_user_left_column > div > span,
    .edit_user_right_column > div > span {
        display: block;
        margin-top: 5px;
    }
    .edit_user_contacts {
        margin-top: 45px;
    }
    .edit_user_buttons {
        display: flex;
        justify-content: center;
    }
    .edit_user_buttons button, .edit_user_buttons a {
        display: block;
        width: 120px;
        height: 50px;
        line-height: 50px;
        text-align: center;
        border-radius: 5px;
        text-transform: uppercase;
    }
    .edit_user_buttons button {
        background-color: #1875F0;
        color: #fff;
        margin-right: 10px;
    }
    .edit_user_buttons button:focus {
        outline: none;
    }
    .edit_user_buttons a {
        border: 2px solid #F5F5F5;
        color: #B3B3B3;
        margin-left: 10px;
    }
    label {
        font-size: 14px;
    }
    .dashboard_users_wrap /deep/ label {
        margin-top: 0;
        color: #666666;
    }
    .dashboard_users_wrap /deep/ .theme--light.v-input input {
        color: #808080;
    }
    .dashboard_users_wrap /deep/ .theme--light.v-messages {
        color: #FF0000;
    }
    .dashboard_users_wrap /deep/ label {
        margin-bottom: 0!important;
    }
    .dashboard_users_wrap /deep/ .v-text-field .v-label--active {
        max-width: 133%;
        transform: translateY(-18px) scale(1,1);
        font-size: 12px;
    }
    .dashboard_users_wrap /deep/ .theme--light.v-select .v-select__selection--comma {
        color: #808080;
    }
    @media (max-width: 1140px) {
        .edit_user_header_form {
            padding-right: 0px;
            padding-left: 0px;
        }
        .edit_user_left_column,
        .edit_user_admin_success > .admin_success_title {
            padding-left: 0px;
            padding-right: 0px;
        }
        .edit_user_right_column, .edit_user_header_form/*, .edit_user_form*/ {
            padding-right: 0px;
            padding-left: 0px;
        }
    }
</style>

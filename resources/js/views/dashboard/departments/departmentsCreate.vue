<template>
<div class="department-create">
    <div class="container">
        <div class="container-fluid dashboard_dep_header">
            <div class="dashboard_dep">
                <div class="row">
                    <div class="col-12">
                        <span class="dashboard_dep_title">Создание отдела</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid dashboard_dep_wrap">
            <div class="dashboard_dep">
                <div class="row">
                    <div class="col-12">
                        <div class="col-12 d-flex align-items-center justify-content-center">
                            <form class="col-md-6">
                                <div>
                                    <v-text-field
                                        placeholder="Введите название"
                                        v-model="name"
                                        label="Название:"
                                        :error-messages="name_error_text"
                                    ></v-text-field>
                                </div>
                                <div>
                                    <v-select v-model="director_user_id"
                                              placeholder="Выберите руководителя отдела"
                                              :items="users"
                                              item-value="id"
                                              item-text="name"
                                              label="Руководитель:"
                                              @change="employeeListLength = 1"
                                              :error-messages="director_user_id_error_text"
                                    ></v-select>
                                </div>
                                <div>
                                    <v-text-field
                                        v-model="employeeListLength"
                                        label="Количество сотрудников:"
                                        readonly
                                        hide-details
                                    ></v-text-field>
                                </div>
                                <div class="button_block pt-4">
                                    <button @click="createDepartment" type="button" class="btn btn-primary">Сохранить</button>
                                    <router-link to="/dashboard/departments">
                                         <button type="button" class="btn btn-outline-secondary department-create-cancel-button">Отмена</button>
                                    </router-link>
                                </div>
                            </form>
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
        name: "departmentsCreate",
        data() {
            return {
                name: '',
                director_user_id: '',
                users:[],
                employeeListLength: 0,
                name_error_text: '',
                director_user_id_error_text: '',
            }
        },
        methods: {
            getUsers() {
                axios.get('/api/dashboard/users')
                    .then(response => {
                        this.users = response.data;
                    });
            },
            createDepartment() {
                this.name_error_text = '';
                this.director_user_id_error_text = '';
                const data = new FormData();
                data.append('name', this.name);
                data.append('director_user_id', this.director_user_id);
                if(this.director_user_id && this.name) {
                axios.post('/api/dashboard/department', data)
                    .then(() => {
                        alert('Данные успешно сохранены');
                        document.querySelector('.department-create-cancel-button').click();
                    })
                    .catch(error => {
                        if (error.response.data.errors) {
                            if (error.response.data.errors.name) {
                                this.name_error_text = error.response.data.errors.name[0];
                            }
                            if (error.response.data.errors.director_user_id) {
                                this.director_user_id_error_text = error.response.data.errors.director_user_id[0];
                            }
                        }
                    });
            }
            else {
                    if (!this.name) {
                        this.name_error_text = 'Поле обязательно к заполнению';
                        return;
                    }
                    if (!this.director_user_id) {
                        this.director_user_id_error_text = 'Поле обязательно к заполнению';
                    }
                }
            }
        },
        created() {
            this.getUsers();
            }

    }
</script>

<style scoped>
    .dashboard_dep {
        background-color: #fff;
    }
    .dashboard_dep_header {
        padding: 10px 15px 8px 15px;
    }
    .dashboard_dep_title {
        font-weight: 300;
        font-size: 36px;
        color: #808080;
        padding-left: 14px;
        line-height: 90px;
    }
    .dashboard_dep_wrap {
        padding: 0 15px;
    }
    .edit_user_left_column > div > span,
    .edit_user_right_column > div > span {
        display: block;
        margin-top: 5px;
    }
    .button_block {
        display: flex;
        justify-content: center;
    }
    .department-create button {
        margin-bottom: 14px;
        margin-left: 10px;
        height: 50px;
        width: 120px;
    }
    .v-overflow-btn .v-input__slot::before {
        border-color: grey !important;
    }
    .form-group {
        max-width: 380px;
        margin: auto;
    }
    .v-text-field--outlined fieldset {
        border-radius: 4px;
        border: 2px solid #F5F5F5;
    }
    .v-input--is-focused fieldset {
        border-radius: 4px;
        border: 2px solid #F5F5F5!important;
    }
    .department-create form a {
        display: block;
        text-align: center;
        font-weight: 500;
        font-size: 14px;
        line-height: 30px;
    }
    .department-create div, .department-create input, .department-create button, .department-create select {
        font-family: Roboto;
        font-style: normal;
    }
    .inputclass .main-title, .general, .contacts, .title, .buttons {
        background-color: #FFFFFF;
    }
    .department-create div {
        font-weight: 500;
        font-size: 14px;
        line-height: 30px;
        color: #666666;
    }
    .department-create select {
        height: 60px;
        padding-top: 1px;
        paddig-bottom: 1px;
    }
    .department-create button {
        font-weight: 900;
        font-size: 12px;
        line-height: 20px;
        text-align: center;
        text-transform: uppercase;
        box-sizing: border-box;
        border-radius: 4px;
        height: 50px;
        width: 165px;
        margin: 0 15px;
    }
    .department-create label {
        margin-top: 28px;
    }
    .department-create /deep/ label {
        margin-top: 0;
        color: #666666;
    }
    .department-create /deep/ .theme--light.v-input input {
        color: #808080;
    }
    .department-create /deep/ .theme--light.v-messages {
        color: #FF0000;
    }
    .department-create /deep/ label {
        margin-bottom: 0!important;
    }
    .department-create /deep/ .v-select__selection{
        color: #808080;
    }
    .department-create /deep/ .v-text-field .v-label--active {
        max-width: 133%;
        transform: translateY(-18px) scale(1,1);
        font-size: 12px;
    }
</style>

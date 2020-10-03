<template>
<div class="container">
    <div class="department-edit">
        <div class="container-fluid dashboard_dep_header">
            <div class="dashboard_dep">
                <div class="row">
                    <div class="col-12">
                        <span class="dashboard_dep_title">Редактирование отдела</span>
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
                                        label="Название:"
                                        v-model="department.name"
                                        :error-messages="name_error_text"
                                    ></v-text-field>
                                </div>
                                <div>
                                    <v-select v-model="department.director_user_id"
                                              placeholder="Временно не выбран"
                                              label="Руководитель:"
                                              :items="users"
                                              item-value="id"
                                              item-text="name"
                                              :error-messages="director_user_id_error_text"
                                    ></v-select>
                                </div>
                                <div>
                                    <label>Количество сотрудников:</label>
                                    <v-text-field
                                        v-model="employeeListLength"
                                        :append-icon="showIcon ? 'mdi-eye' : 'mdi-eye-off'"
                                        readonly
                                        hide-details
                                        @click:append="showEmployeeList"
                                    ></v-text-field>
                                </div>
                                <div class="button_block pt-4">
                                    <button @click="departmentSave" type="button" class="btn btn-primary">Сохранить</button>
                                    <router-link to="/dashboard/departments">
                                        <button type="button" class="btn btn-outline-secondary department-create-cancel-button">Отмена</button>
                                    </router-link>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div v-show="showModal" class="modal-mask">
                <employees-list :opened="showModal"
                                @close="closeModal"

                                @saveSettings="changeEmployees($event)"
                                :employees="department.users">
                </employees-list>
            </div>
        </div>
    </div>
</div>
</template>

<script>
    import Departments from "./departments";
    export default {
        name: "departmentsEdit",
        components: {Departments},
        data() {
            return {
                id: this.$route.params.id,
                department: {},
                users: [],
                showModal: false,
                showIcon: false,
                name_error_text: '',
                director_user_id_error_text: '',
                employeeListLength: 0,
            }
        },
        mounted() {
            this.edit();
        },
        created(){
            this.getUsers();
        },
        methods: {
            showEmployeeList() {
                if (this.employeeListLength > 0) {
                    this.showModal = true;
                    document.querySelector('.modal-mask').style.zIndex = 1000;
                    document.querySelector('.modal-mask').style.opacity = 1;
                } else {
                    alert("В данном отделе нет сотрудников");
                }
            },
            edit() {
                axios.get(`/api/dashboard/department/${this.id}/edit`)
                    .then(response => {
                        this.department = response.data[0]
                        if (this.department.users) {
                            this.employeeListLength = this.department.users.length;
                            if (this.department.users.length > 0) {
                                this.showIcon = true;
                            } else {
                                this.showIcon = false;
                            }
                        }
                    });
            },
            getUsers() {
                axios.get('/api/dashboard/users')
                    .then(response => {
                        this.users = response.data;
                    });
            },
            departmentSave(changedByModal = false) {
                this.name_error_text = '';
                this.director_user_id_error_text = '';
                const data = new FormData();
                data.append('name', this.department.name);
                if (this.department.director_user_id) {
                    data.append('director_user_id', this.department.director_user_id);
                }
                data.append('id', this.department.id);
                axios.post('/api/dashboard/department/update', data)
                    .then(() => {
                        if(!this.changedByModal) {
                            alert('Данные успешно сохранены');
                            document.querySelector('.department-create-cancel-button').click();
                        }
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
            },
           async changeEmployees(event) {
                if (event.indexOf(this.department.director_user_id) != -1) {
                    this.department.director_user_id = null;
                   await this.departmentSave(true);
                }
                axios.post(`/api/dashboard/users/mass_update`, {
                    idsToChange: event,
                    columnToUpdate: 'department_id',
                    updateValue: null,
                })
                    .then(() => {
                        this.edit();
                        this.$forceUpdate();
                    });
                this.showEmployeeList();
                this.edit();
                document.querySelector('.modal-mask').style.opacity = 0;
                document.querySelector('.modal-mask').style.zIndex = -1000;
                this.showModal = false;
            },
            closeModal() {
                this.showEmployeeList();
                this.edit();
                document.querySelector('.modal-mask').style.opacity = 0;
                document.querySelector('.modal-mask').style.zIndex = -1000;
                this.showModal = false;
            }
        },
        watch:  {
            'department.director_user_id': function () {
                if (this.department.director_user_id && this.employeeListLength < 1) {
                    this.employeeListLength = 1;
                }
            }
        }
    }
</script>


<style scoped>
    .modal-mask {
        z-index: -1000;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        display: table;
        opacity: 0;
    }
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

    .edit_dep_form span {
        line-height: 30px;
    }

    .edit_dep_left_column > div > span,
    .edit_dep_right_column > div > span {
        display: block;
        margin-top: 5px;
    }
    .button_block {
        display: flex;
        justify-content: center;
    }

    button {
        margin-bottom: 14px;
        margin-left: 10px;
        height: 50px;
        width: 120px;
    }
    form a {
        display: block;
        text-align: center;
        font-weight: 500;
        font-size: 14px;
        line-height: 30px;
    }
    div, input, button, select {
        font-family: Roboto;
        font-style: normal;
    }
    input, select, v-select, .main-title, .general, .contacts, .title, .buttons {
        background-color: #FFFFFF;
    }
    .v-text-field--outlined fieldset{
        border: 2px solid !important;
        bottom: 0;
        left: 0;
        pointer-events: none;
        position: absolute;
        right: 0;
        top: -5px;
        transition-duration: .3s;
        transition-property: color,border-width;
        transition-timing-function: cubic-bezier(.25,.8,.25,1);
    }
    .theme--light.v-text-field--outlined:not(.v-input--is-focused):not(.v-input--has-state)>.v-input__control>.v-input__slot fieldset {
        color: #F5F5F5;
    }
    div {
        font-weight: 500;
        font-size: 14px;
        line-height: 30px;
        color: #666666;
    }
    input, select, v-select {
        font-weight: bold;
        font-size: 14px;
        line-height: 60px;
        color: #808080;
        border: 2px solid #F5F5F5;
        box-sizing: border-box;
        border-radius: 4px;
        padding-left: 24px;
        padding-right: 24px;
        width: 100%;
    }
    select {
        height: 60px;
        padding-top: 1px;
        paddig-bottom: 1px;
    }
    button {
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
    label {
        margin-top: 28px;
    }
    .department-edit /deep/ label {
        margin-top: 0;
        color: #666666;
    }
    .department-edit /deep/ .theme--light.v-input input {
        color: #808080;
    }
    .department-edit /deep/ .theme--light.v-messages {
        color: #FF0000;
    }
    .department-edit /deep/ label {
        margin-bottom: 0!important;
    }
    .department-edit /deep/ .v-select__selection{
        color: #808080;
    }
    .department-edit /deep/ .v-text-field .v-label--active {
        max-width: 133%;
        transform: translateY(-18px) scale(1,1);
        font-size: 12px;
    }
</style>

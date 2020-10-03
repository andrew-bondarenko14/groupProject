<template>
        <div class="modal_body"
             :class="opened ? 'modal_opened' : ''">
        <h4>Список сотрудников</h4>
            <ul>
                <li v-for="(employee,index) in employeesList">
                    {{ employee.name }}
                    <span class="delete_button" @click="deleteEmployee(employee)"></span>
                </li>
                <li v-if="employeesListLength == 0">
                    В этом отделе нет работников
                </li>
            </ul>
            <div class="buttons">
                <button @click="saveChanges" class="btn btn-primary">Сохранить</button>
                <button @click="close" type="button" class="btn btn-outline-secondary">Отмена</button>
            </div>
        </div>
</template>

<script>
    import EmployeesList from "./employeesList";
    export default {
        components: {EmployeesList},
        props: ['opened','employees'],
        data: function() {
            return {
                employeesList: [],
                employeesListNotDeleted: [],
                toDelete: [],
                employeesListLength: 0,
            }
        },
        watch: {
            employees : function () {
                this.employeesList = this.employees;
                if (this.employeesList) {
                    this.employeesListLength = this.employeesList.length;
                }
            },
            columnsToShow: function() {
                this.columns = this.columnsToShow;
            }
        },
        methods: {
            close() {
                this.$emit('close');
                this.toDelete = [];
            },
            saveChanges() {
                this.$emit('saveSettings',this.toDelete);
                this.toDelete = [];
            },
            deleteEmployee(employee)  {
                this.toDelete.push(employee.id);
                this.employeesList.splice(this.employeesList.indexOf(employee),1);
            }
        },
    }
</script>
<style scoped>

    .modal_body {
        position:fixed;
        left: 50%;
        top: -150%;
        transform: translate(-50%,-50%);
        transition: top .5s ease-in-out;
        background: #FFFFFF;
        box-shadow: 0px 5px 30px rgba(0, 0, 0, 0.07);
        border-radius: 6px;
        width: 100%;
        padding: 18px 10px 10px 45px;
        z-index: 100;
        max-width: 444px;
    }
    .modal_body h4 {
        font-style: normal;
        font-weight: 500;
        font-size: 18px;
        line-height: 30px;
        color: #666666;
    }

    .modal_body p {
        font-style: normal;
        font-weight: normal;
        font-size: 12px;
        line-height: 20px;
        color: #666666;
        white-space: pre-line;
    }
    .delete_button {
        position: absolute;
        right: 15px;
        top: 10px;
        width: 26px;
        height: 26px;
        opacity: 0.3;
    }
    .delete_button:hover {
        opacity: 1;
    }
    .delete_button:before, .delete_button:after {
        position: absolute;
        left: 15px;
        content: ' ';
        height: 20px;
        width: 2px;
        background-color: #333;
    }
    .delete_button:before {
        transform: rotate(45deg);
    }
    .delete_button:after {
        transform: rotate(-45deg);
    }

    .modal_opened {
        top: 50%;
    }
    label {
        font-family: Roboto;
        font-style: normal;
        font-weight: 500;
        font-size: 12px;
        line-height: 30px;
        color: #666666;
    }
    input {
        font-style: normal;
        font-weight: bold;
        font-size: 12px;
        line-height: 40px;
        color: #808080;
    }
    .email-group {
        max-width: 225px;
    }
    .buttons {
        display: flex;
        justify-content: flex-start;
    }
    input {
        height: 40px;
        width: 225px;
    }
    .buttons button{
        height: 50px;
        margin-right: 10px;
        width: 120px;
    }


    ul {
        list-style: none;
        background: #FFFFFF;
        box-shadow: 0px 5px 30px rgba(0, 0, 0, 0.07);
        border-radius: 6px;
        max-width: 361px;
    }
    ul li {
        justify-content: center;
        display: flex;
        align-items: center;
        font-style: normal;
        font-weight: 500;
        font-size: 13px;
        line-height: 60px;
        height: 60px;
        color: #666666;
        border-bottom: 1px solid #F5F5F5;;
        position: relative;
    }
    ul li:last-of-type {
        border-bottom: none;
    }
    ul li .delete_button {
        position: absolute;
        right: 0%;
        top: 50%;
        transform: translate(-50%, -50%);
        display: flex;
        justify-content: center;
        align-items: center;
    }
    label {
        display: flex;
        width: 100%;
        margin-bottom: 13px;
    }


</style>

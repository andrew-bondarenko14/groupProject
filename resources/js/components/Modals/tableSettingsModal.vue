<template>
        <div class="modal_body"
             :class="opened ? 'modal_opened' : ''">
        <h4>Управление  столбцами таблицы</h4>
            <ul>
                <li>
                    <label>
                        <div>Столбец:</div>
                        <div>Отображать:</div>
                    </label>
                </li>
                <li v-for="(column,index) in columns">
                    <label>
                        <div>{{ column.text }}</div>
                        <div>
                            <input type="checkbox" hidden v-model="column.visible">
                            <i class="custom-checkbox"></i>
                        </div>
                    </label>
                </li>
            </ul>
            <div class="buttons">
                <button @click="changeSettings" class="btn btn-primary">Сохранить</button>
                <button @click="close" type="button" class="btn btn-outline-secondary">Отмена</button>
            </div>
        </div>
</template>

<script>
    export default {
        props: ['opened','columnsToShow'],
        data: function() {
            return {
                columns : [],
            }
        },
        methods: {
            close() {
                this.$emit('close');
            },

            changeSettings() {
                let result = [];
                this.columnsToShow.forEach(function (item) {
                    if (item.visible == true)
                        result.push(item);
                })
                this.columns = this.columnsToShow;
                this.$emit('saveSettings',result);
            },

        },
        watch: {
            columnsToShow: function() {
                this.columns = this.columnsToShow;
            }
        }
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
        max-width: 540px;
        padding: 18px 10px 10px 45px;
        z-index: 100;
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
    .close {
        position: absolute;
        right: 15px;
        top: 10px;
        width: 26px;
        height: 26px;
        opacity: 0.3;
    }
    .close:hover {
        opacity: 1;
    }
    .close:before, .close:after {
        position: absolute;
        left: 15px;
        content: ' ';
        height: 26px;
        width: 2px;
        background-color: #333;
    }
    .close:before {
        transform: rotate(45deg);
    }
    .close:after {
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
    .custom-checkbox {
        display: inline-block;
        width: 20px;
        height: 20px;
        cursor: pointer;
        background-color: #FFFFFF;
        border: 2px solid #E6E6E6;
        box-sizing: border-box;
        border-radius: 4px;
        vertical-align: middle;
        position: relative;
        margin-left: 5px;
    }
   input:checked + .custom-checkbox {
       background-color: #1875F0;
       border: none;
    }
    input:checked + .custom-checkbox:after {
        content: '';
        display: block;
        position: absolute;
        top: 3px;
        left: 7px;
        width: 7px;
        height: 12px;
        border: solid #ffffff;
        border-width: 0 3px 3px 0;
        transform: rotate(45deg);
    }
    ul {
        list-style: none;
    }
    ul li {
        display: flex;
        align-items: center;
        font-style: normal;
        font-weight: 500;
        font-size: 14px;
        line-height: 30px;
        color: #666666;

    }
    ul li div:first-of-type {
        max-width: 70%;
        width: 100%;
    }
    ul li div:last-of-type {
        max-width: 30%;
        width: 100%;
    }
    label {
        display: flex;
        width: 100%;
        margin-bottom: 13px;
    }


</style>

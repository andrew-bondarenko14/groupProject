<template>
        <div class="modal_body"
             :class="opened ? 'modal_opened' : ''">
        <h4>Кодовое слово при восстановлении пароля</h4>
            <div v-if="error" class="error text-center pb-3">{{ errorText}}</div>
            <span @click="close" class="close"></span>
            <form class="change_password-form" @submit.prevent="changeCodeword">
                <div class="form-group codeword-group">
                    <styled-input v-model="codeword" required class="form-control" aria-describedby="inputToken"
                                  label="Введите кодовое слово"></styled-input>
                </div>
                <div class="buttons">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <button @click="close" type="button" class="btn btn-outline-secondary">Отмена</button>
                </div>
            </form>
        </div>
</template>

<script>
    export default {
        props: ['opened'],
        data: function() {
            return {
                codeword: '',
                error: false,
                errorText: ''
            }
        },
        methods: {
            close() {
                this.error = false;
                this.codeword = '';
                this.$emit('close');
            },
            updateCodeword() {
                this.error = false;
                this.$emit('setCodeword',this.codeword);
                this.codeword = '';
            },
            changeCodeword() {
                this.error = false;
                if (this.codeword.length > 3) {
                    this.updateCodeword();
                } else {
                    this.error = true;
                    this.errorText = 'Кодовое слово не может быть меньше 3 символов!';
                }
            },
        }
    }
</script>
<style scoped>

    .modal_body {
        position:fixed;
        left: 50%;
        top: -50%;
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

</style>

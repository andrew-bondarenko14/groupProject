<template>
        <div class="modal_body"
                 :class="opened ? 'modal_opened' : ''">
            <h4>Смена пароля</h4>
            <p>Ввведите, пожалуйста, новый пароль для следующих авторизаций:</p>
            <div v-if="error" class="error text-center pb-3">{{ errorText}}</div>
            <form class="change_password-form" @submit.prevent="changePassword">
                <div class="form-group password-group">
                    <styled-input v-model="password" :type="passwordInputType" required class="form-control"
                                  id="inputPassword" aria-describedby="inputToken" label="Введите пароль"></styled-input>
                </div>
                <div class="form-group password-group">
                    <styled-input v-model="passwordConfirm" :type="confirmInputType" required class="form-control"
                           id="inputConfirm" aria-describedby="inputToken" label="Введите подтверждение пароля">
                    </styled-input>
                </div>
                <div class="buttons">
                    <button type="submit" class="btn btn-primary">Сохранить</button>
                    <button @click="close" type="button" class="btn btn-outline-secondary">Отмена</button>
                </div>
            </form>
            <span @click="close" class="close"></span>
        </div>
</template>

<script>
    export default {
        props : ['opened'],
        data : function() {
          return {
              passwordInputType: 'password',
              confirmInputType: 'password',
              errorText: '',
              password: '',
              passwordConfirm: '',
              error: false
          }
        },
        methods: {
            close() {
                this.error = false;
                this.password = '';
                this.passwordConfirm = '';
                this.$emit('close');
            },
            changePassword() {
                this.error = false;
                let th = this;
                if (/^(((?=.*[a-z])(?=.*[A-Z]))|((?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[0-9])))(?=.{6,})/.test(this.password)) {
                    if (this.password === this.passwordConfirm) {
                        axios.post('/api/user/security/change/password', {
                            'password': this.password,
                            'passwordConfirm': this.passwordConfirm
                        })
                            .then((response) => {
                                if (response.data === true) {
                                    Vue.swal.fire(
                                        'Изменение пароля',
                                        'Ваш пароль был успешно изменен',
                                        'success'
                                    );
                                    th.close();
                                } else {
                                    th.error = true;
                                    th.errorText = response.data;
                                }
                            })
                    } else {
                        this.error = true;
                        this.errorText = 'Пароли не совпадают!'
                    }
                } else {
                    this.error = true;
                    this.errorText = 'Пароль должен содержать не менее 6 латинских символов, цифр, букв и знаков препинания.';
                }
            },
            togglePassword(input) {
                let inputToChange = (input === 'password' ? this.passwordInputType : this.confirmInputType);
                if (inputToChange == 'password')
                    (input === 'password' ? this.passwordInputType = 'text' : this.confirmInputType = 'text');
                else
                    (input === 'password' ? this.passwordInputType = 'password' : this.confirmInputType = 'password');
            }
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
    .password-group {
        max-width: 225px;
    }
    .show_password {
        position: absolute;
        background-image: url("/storage/show_password.png");
        background-size: contain;
        right: 10px;
        content: "";
        display: inline-block;
        cursor: pointer;
        width: 26px;
        height: 26px;
        background-repeat: no-repeat;
        bottom: 7px;
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

</style>

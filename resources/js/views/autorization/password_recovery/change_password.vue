<template>
    <div class="page_bg">
        <div class="container">
            <div class="auth_container">
                <h3>Назначение нового пароля</h3>
                <div v-if="!error && !changeWithCodeword" class="text-center pb-3"> Ввведите, пожалуйста, новый пароль:</div>
                <div v-if="!error && changeWithCodeword" class="text-center pb-3"> Ввведите, пожалуйста ваше кодовое слово и новый пароль:</div>
                <div v-if="error" class="error text-center pb-3">{{ errorText}}</div>
                <form class="change_password-form" @submit.prevent="changePassword">
                    <div v-if="changeWithCodeword" class="form-group password-group">
                        <styled-input v-model="codeword" :type="passwordInputType" required class="form-control"
                                      id="inputCodeword" aria-describedby="inputToken" label="Кодовое слово">
                        </styled-input>
                    </div>
                    <div class="form-group password-group">
                        <styled-input v-model="password" :type="passwordInputType" required class="form-control"
                                      id="inputPassword" aria-describedby="inputToken" label="Новый пароль">
                        </styled-input>
                    </div>
                    <div class="form-group password-group">
                        <styled-input v-model="passwordConfirm" :type="confirmInputType" required class="form-control"
                                      id="inputConfirm" aria-describedby="inputToken" label="Подтвердите пароль">
                        </styled-input>
                    </div>
                    <div class="buttons">
                        <button type="submit" class="btn btn-primary">
                            <span v-show="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Далее</button>
                        <button @click="cancel" type="button" class="btn btn-outline-secondary">Отмена</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    import authorization from "../authorization";

    export default {
        data: function () {
            return {
                email: '',
                password: '',
                passwordConfirm: '',
                token: '',
                error: false,
                timeleft: 60,
                passwordInputType: 'password',
                confirmInputType: 'password',
                errorText: '',
                loading: false,
                changeWithCodeword: false,
                codeword: ''
            }
        },
        mounted() {
            this.countDown();
            let th = this;
            if (this.$route.query.t !='codeword') {
                axios.post('/api/recovery/verify', {
                    'token': this.$route.query.t
                })
                    .then((response) => {
                        th.email = response.data;
                    })
                    .catch((error) => {
                        this.$router.push({
                            'name': 'authorization'
                        });
                    });
            } else {
                this.changeWithCodeword = true;
                if (localStorage.email)
                    this.email = localStorage.email;
                else
                    this.$router.push({ 'name': 'authorization'})
            }

        },
        methods: {
            changePassword() {
                let th = this;
                if (/^(((?=.*[a-z])(?=.*[A-Z]))|((?=.*[a-z])(?=.*[0-9]))|((?=.*[A-Z])(?=.*[0-9])))(?=.{6,})/.test(this.password)) {
                    if (this.password === this.passwordConfirm) {
                        this.loading = true;
                        axios.post('/api/recovery/change', {
                            'email': this.email,
                            'password': this.password,
                            'passwordConfirm': this.passwordConfirm,
                            'codeword': this.codeword ?? null,
                            'token': this.$route.query.t
                        })
                            .then((response) => {
                                this.error = false;
                                th.loading = false;
                                if (response.data === true) {
                                    Vue.swal.fire(
                                        'Изменение пароля',
                                        'Ваш пароль был успешно изменен',
                                        'success'
                                    );
                                    th.$router.push({
                                        name: 'authorization'
                                    });
                                } else {
                                    th.loading = false;
                                    th.error = true;
                                    th.errorText = response.data;
                                }
                            })
                    } else {
                        th.loading = false;
                        this.error = true;
                        this.errorText = 'Пароли не совпадают!'
                    }
                } else {
                    th.loading = false;
                    this.error = true;
                    this.errorText = 'Пароль должен содержать не менее 6 латинских символов, цифр, букв и знаков препинания.';
                }
            },
            cancel() {
                this.$router.push({
                    name: 'authorization'
                })
            },
            countDown() {
                setInterval(() => {
                    if (this.timeleft > 0)
                        this.timeleft--;
                }, 1000)
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
    body {
        margin : 0;
    }
    .page_bg {
        background-image: url("/storage/auth_bg.png");
        background-size: cover;
        width: 100%;
        height: 100vh;
        display: flex;
        align-items: center;
    }
    .auth_container * {
        font-family: Roboto;
    }
    .auth_container h3 {
        font-style: normal;
        font-weight: 500;
        font-size: 24px;
        line-height: 60px;
        text-align: center;
        color: #606060;
    }
    .auth_container form {
        max-width: 300px;
        margin: auto;
    }
    .auth_container form a {
        display: block;
        text-align: center;
        font-weight: 500;
        font-size: 14px;
        line-height: 30px;
    }
    .auth_container .buttons {
        display: flex;
        justify-content: space-around;
    }
    .auth_container input {
        height: 60px;
        min-width: 296px;
    }
    .auth_container .buttons button{
        height: 50px;
        width: 120px;
    }
    .auth_container .auth_error {
        font-weight: 500;
        font-size: 14px;
        line-height: 30px;
        text-align: center;
        color: #FF0000;
        margin: auto;
    }
    .error {
        color: #FF0000;
        font-weight: 500;
    }
    .info_block {
        font-style: normal;
        font-weight: 500;
        font-size: 10px;
        text-align: center;
        line-height: 23px;
        color: #666666;
        min-width: 167px;
    }
    .confirm_form {
        max-width: 450px!important;
    }
    .confirm_form .buttons {
        max-width: 300px;
        margin: auto;
    }
    .password-group {
        position: relative;
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
        bottom: 15px;
    }
</style>

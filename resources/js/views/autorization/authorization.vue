<template>
    <div class="page_bg">
        <div class="container">
            <div class="auth_container">
                <h3>Авторизация</h3>
                <form  method="post"
                       ref="auth_form"
                       @submit.prevent="validate">
                    <input type="hidden" name="_token" :value="csrf">
                    <div v-if="login_error" class="auth_error">{{ errorText }}</div>
                    <div class="form-group">
                        <styled-input v-model="email" type="email" :disabled="twoFAinProgress" name="email"
                               required class="form-control" id="inputEmail" aria-describedby="emailHelp"
                                      label="Введите email"> </styled-input>
                    </div>
                    <div class="form-group mb-1 password-group">
                        <styled-input v-model="password" name="password" :disabled="twoFAinProgress"
                                      required :type="passwordInputType" class="form-control" id="inputPassword" label="Введите пароль"></styled-input>
                    </div>
                    <div v-show="!twoFAinProgress" class="form-group">
                        <router-link to="authorization/recovery" href="#">Забыли пароль?</router-link>
                    </div>
                    <div v-if="twoFAPasswordAvailable && twoFAinProgress && !emailVerificationSelected"
                         :style="!twoFAEmailAvailable ? 'max-width:300px' : ''"
                         class="form-group two_auth-block" :class="emailVerificationSelected ? 'hidden' : ''">
                        <p v-if="twoFAEmailAvailable && twoFAPasswordAvailable">Воспользуйтесь одним из двух вариантов подтверждения:</p>
                        <p v-if="twoFAEmailAvailable && !twoFAPasswordAvailable">Введите код отправленный Вам на почту что бы войти</p>
                        <div class="confirm_group">
                            <div v-if="twoFAEmailAvailable">
                                <button @click="selectEmail" type="button" class="btn btn-outline-secondary">ПОЛУЧИТЬ КОД</button>
                            </div>
                            <div :style="!twoFAEmailAvailable ? 'margin:auto' : ''">
                                <label v-if="twoFAEmailAvailable && twoFAPasswordAvailable">Ввести резервный пароль:</label>
                                <styled-input v-model="twoFACode" name="backupPassword" required class="form-control" id="backupPassword" label="Введите резервный пароль"></styled-input>
                            </div>
                        </div>
                    </div>
                    <div v-if="twoFAinProgress && emailVerificationSelected" class="d-flex form-group FA_email_group confirm_group mt-3">
                        <div>
                            <styled-input style="margin-top: 31px" v-model="twoFACode" required class="form-control" id="inputToken" aria-describedby="inputToken" label="Код подтверждения"></styled-input>
                        </div>
                        <div class="ml-sm-3 info_group">
                            <label class="info_block" :style="timeleft > 0 ? '' : 'opacity:0;'">Станет доступно через: {{ timeleft}} сек.</label>
                            <button @click="sendEmail" :disabled="timeleft > 0"  type="button" class="btn btn-outline-secondary">ОТПРАВИТЬ ПОВТОРНО</button>
                        </div>
                    </div>
                    <div class="login-button">
                        <vue-recaptcha
                            name="recaptchaToken"
                            ref="recaptcha"
                            size="invisible"
                            :sitekey="sitekey"
                            @verify="login"
                            @expired="onCaptchaExpired"
                        />
                        <button type="submit" class="btn btn-primary">
                            <span v-show="loading" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                            Войти</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>
<script>
    export default {
        props: ['flashed_email'],
        data: function () {
            return {
                email: '',
                password: '',
                login_error: false,
                emailError: false,
                passwordError: false,
                passwordInputType: 'password',
                sitekey: process.env.MIX_RECAPTCHA_SITE_KEY,
                loading: false,
                csrf: document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                twoFAinProgress: false,
                twoFACode: '',
                twoFAEmailAvailable: false,
                twoFAPasswordAvailable: false,
                emailVerificationSelected: false,
                timeleft: 60,
                twoFAAuthError: false,
                errorText: 'Вы ввели неверный e-mail или пароль',
            }
        },
        created() {
            if (this.flashed_email) {
                this.login_error = true;
                this.email = this.flashed_email;
            }
        },
        methods: {
            login(recaptchaToken) {
                this.emailError = false;
                this.passwordError = false;
                let th = this;
                th.loading = true;
                axios.post('/login', {
                    email: this.email,
                    password: this.password,
                    recaptchaToken: recaptchaToken,
                    twoFACode: this.twoFACode ?? null,
                    leaved_at: localStorage.getItem('leaved_at') ?? new Date(),
                    csrf : th.csrf
                })
                    .then((response) => {
                        if (response.data.redirect) {
                            th.loading = false;
                            th.login_error = false;
                            location.href = response.data.redirect;
                        }
                        if (response.data.twoFAMethods) {
                            th.loading = false;
                            th.login_error = false;
                            th.twoFAinProgress = true;
                            th.twoFAPasswordAvailable = response.data.twoFAMethods.passwordAuth;
                            th.twoFAEmailAvailable = response.data.twoFAMethods.emailAuth;
                            if(!th.twoFAPasswordAvailable) {
                                th.emailVerificationSelected = true;
                                th.countDown();
                                th.sendEmail();
                            }
                        }
                    })
                    .catch((error) => {
                        th.loading = false;
                        th.login_error = true;
                        if (!th.twoFAinProgress) {
                            th.errorText = 'Вы ввели неверный e-mail или пароль';
                            th.password = '';
                        }
                        else
                            th.errorText = 'Вы ввели неверный код двухфакторной авторизации';
                    });
            },
            validate() {
                this.$refs.recaptcha.reset();
                this.$refs.recaptcha.execute();
            },
            togglePassword() {
                if (this.passwordInputType == 'password')
                    this.passwordInputType = 'text';
                else
                    this.passwordInputType = 'password';
            },
            onCaptchaExpired() {
                this.$refs.recaptcha.reset()
            },
            selectEmail() {
                this.emailVerificationSelected = true;
                this.countDown();
                this.sendEmail();
                this.twoFACode = '';
            },
            countDown() {
                setInterval(() => {
                    if (this.timeleft > 0)
                        this.timeleft--;
                }, 1000)
            },
            sendEmail() {
                    let th = this;
                    axios.post('/api/2fa/send', {
                        'email': this.email
                    })
                        .then((response) => {
                            if (response.data != false) {
                                th.timeleft = 60;
                            }
                        })
                        .catch((error) => {
                            console.log(error);
                        });
            }
        }
    }
</script>

<style>
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
    .auth_container {
        max-width: 573px;
        width: 100%;
        margin: auto;
        background-color: #fff;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.05);
        border-radius: 6px;
        padding: 0 10px;
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
        max-width: 573px;
        width: 100%;
        margin: auto;
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    .auth_container form a {
        display: block;
        text-align: center;
        font-weight: 500;
        font-size: 14px;
        line-height: 30px;
    }
    .auth_container .login-button {
        display: flex;
        justify-content: center;
        padding-bottom: 21px;
    }
    .auth_container .buttons {
        display: flex;
        justify-content: center;
        padding-bottom: 21px;
    }
    .auth_container input {
        height: 60px;
    }
    .auth_container .buttons button,
    .auth_container .login-button button
    {
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
    input[type=password] {
        position: relative;
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
    .info_block {
        font-style: normal;
        font-weight: 500;
        font-size: 10px;
        text-align: center;
        line-height: 23px;
        color: #666666;
        min-width: 167px;
    }
    .auth_container form > div {
        max-width: 300px;
        width: 100%;
    }
    .auth_container .two_auth-block {
        max-width: max-content;
        margin: auto;
        transition: height .5s ease-in-out;
        overflow-y: hidden;
    }
    .two_auth-block p {
        text-align: center;
    }
    .two_auth-block > div{
        display: flex;
        width: 100%;
       align-items: flex-end;
    }
    .two_auth-block > div input {
        min-width: 299px;
        max-width: 100%;
    }
    .two_auth-block > div > div:first-of-type {
        margin-right: 70px;
        margin-bottom: 10px;
    }
    .confirm_group {
        max-width: 485px!important;
    }
    .confirm_group input {
        width: 300px!important;
    }


    @media screen and (max-width: 767px) {
        .confirm_group {
            flex-wrap: wrap;
            display: flex;
            flex-direction: column;
            align-items: center!important;
        }
        .confirm_group input{
            width: 100%!important;
            min-width: 100%!important;
        }
        .confirm_group > div:first-of-type {
            margin-right: 0!important;
            display: flex;
            justify-content: center;
            width: 100%;
        }
        .two_auth-block .confirm_group div {
            display: flex;
            flex-direction: column;
            align-items: center;
            padding-bottom: 30px;
            margin-left: 0 !important;
            width: 100%;
            max-width: 300px;
        }
        .info_group {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
    }
    .FA_email_group {
        overflow-y: hidden;
        transition: height .5s ease-in-out;
      }
    .hidden {
        max-height: 0px;
    }
</style>

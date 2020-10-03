<template>
    <div class="page_bg">
        <div class="container">
            <div class="auth_container">
                <h3>Восстановление доступа</h3>
                <div v-if="!confirmation_error" class="text-center pb-3"> На ваш e-mail было отправлено уведомление с кодом подтверждения.</div>
                <div v-if="confirmation_error" class="error text-center  pb-3">Вы ввели неверный код или срок его действия уже истек</div>
                <form class="confirm_form" onsubmit="return false;">
                    <div class="form-group">
                        <div class="d-flex confirm_group ">
                            <div>
                                <styled-input v-model="token" required class="form-control" id="inputToken" aria-describedby="inputToken" label="Введите код сброса пароля"></styled-input>
                            </div>
                            <div v-if="showResend" class="ml-3 info_group">
                                <label class="info_block" :style="timeleft > 0 ? '' : 'opacity:0;'">Станет доступно через: {{ timeleft}} сек.</label>
                                <button @click="resendEmail(null)" :disabled="timeleft > 0"  type="button" class="btn btn-outline-secondary">ОТПРАВИТЬ ПОВТОРНО</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group">
                        <button type="button" @click="resendEmail(backupEmail)" v-if="backupEmail" class="btn-link btn-block ml-0">Отправить код на резервный e-mail</button>
                        <button type="button" @click="changeWithCodeword" v-if="codeword" class="btn-link btn-block ml-0">Установить новый пароль с помощью кодового слова</button>
                    </div>
                    <div class="buttons">
                        <button @click="restorePassword" type="submit" class="btn btn-primary">
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
    export default {
        data: function () {
            return {
                email: '',
                token: '',
                confirmation_error: false,
                timeleft: 60,
                loading: false,
                backupEmail: '',
                codeword: '',
                showResend: true
            }
        },
        mounted() {
            this.countDown();
            if (localStorage.email)
                this.email = localStorage.email;
            else
                this.$router.push({
                    name: 'password_recovery'
                });
            if (localStorage.backupEmail)
                this.backupEmail = localStorage.backupEmail;
            if (localStorage.codeword)
                this.codeword = localStorage.codeword;
        },
        methods: {
            restorePassword() {
                this.loading = true;
                let self = this;
                axios.post('/api/recovery/confirm', {
                    'email': this.email,
                    'token': this.token
                })
                    .then((response) => {
                        self.loading = false;
                        if (response.data == false)
                            this.confirmation_error = true;
                        else {
                            localStorage.clear();
                            this.confirmation_error = false;
                            this.$router.push('/authorization/recovery/change?t=' + response.data);
                        }
                    })
                    .catch((error) => {
                        self.loading = false;
                    });

            },
            cancel() {
                this.$router.go(-1);
            },
            countDown() {
                setInterval(() => {
                    if (this.timeleft > 0)
                        this.timeleft--;
                }, 1000)
            },
            resendEmail(backupEmail = null) {
                if (backupEmail)
                    this.showResend = false;
                console.log(backupEmail);
                let th = this;
                axios.post('/api/recovery/send', {
                    'email': this.email,
                    'backupEmail': backupEmail ?? null
                })
                    .then((response) => {
                        if (response.data != false) {
                            th.timeleft = 60;
                        }
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            },
            changeWithCodeword() {
                this.$router.push('/authorization/recovery/change?t=codeword');
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
        display: flex;
        justify-content: center;
    }
    .confirm_form .buttons {
        max-width: 300px;
        margin: auto;
    }
    .auth_container form > div {
        max-width: 100%;
    }
    .confirm_group div:first-of-type label {
        margin-top: 31px;
    }
    .btn-link {
        outline: none;
    }
    @media screen and (max-width: 767px) {
        .confirm_group {
            flex-wrap: wrap;
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .confirm_group input {
           width: 100%!important;
        }
        .confirm_group div {
            padding-bottom: 30px;
            margin-left: 0!important;
            width: 100%;
            display: flex;
            justify-content: center;
            padding-bottom: 0;

        }
        .info_group {
            display: flex;
            flex-direction: column;
            align-items: center;
        }
        .auth_container {
            padding-left: 10px;
            padding-right: 10px;
        }

    }


</style>

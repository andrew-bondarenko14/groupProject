<template>
    <div class="page_bg">
        <div class="container">
            <div class="auth_container">
                <h3>Восстановление доступа</h3>
                <div class="text-center pb-3">Пожалуйста, укажите email, который Вы использовали для входа в систему.</div>
                <div v-if="recovery_error" class="error text-center">Пользователь с указанным e-mail адресом не найден в системе</div>
                <form class="change_container" method="post"
                      @submit.prevent="validate">
                    <div class="form-group">
                        <styled-input v-model="email" name="email" type="email" required class="form-control" id="inputEmail" aria-describedby="emailHelp" label="Введите email"></styled-input>
                    </div>
                    <div class="form-group">
                        <!--                        <a href="#">Установить новый пароль с помощью кодового слова</a>-->
                    </div>
                    <div class="buttons">
                        <vue-recaptcha
                            ref="recaptcha"
                            size="invisible"
                            :sitekey="sitekey"
                            @verify="restore_password"
                            @expired="onCaptchaExpired"
                        />
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
    export default {
        data: function () {
            return {
                email: '',
                recovery_error: false,
                sitekey: process.env.MIX_RECAPTCHA_SITE_KEY,
                loading: false
            }
        },
        methods: {
            restore_password() {
                this.loading = true;
                let self = this;
                axios.post('/api/recovery/send', {
                    'email': this.email
                })
                    .then((response) => {
                        self.loading = false;
                        if (response.data == false)
                            this.recovery_error = true;
                        else {
                            localStorage.email = this.email;
                            localStorage.backupEmail = response.data.backupEmail;
                            localStorage.codeword = response.data.codeword;
                            this.recovery_error = false;
                            this.$router.push({
                                'name': 'recovery_confirm'
                            })
                        }
                    })
                    .catch((error) => {
                        self.loading = false;
                    });
            },
            cancel() {
                this.$router.push({
                    name: 'authorization'
                })
            },
            onCaptchaExpired() {
                this.$refs.recaptcha.reset()
            },
            validate() {
                this.$refs.recaptcha.reset();
                this.$refs.recaptcha.execute();
            },
        }
    }
</script>

<style scoped>

    .auth_container {
        padding: 0 10px;
    }
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
        width: 100%;
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
    .btn-link {
        outline: none;
    }




</style>

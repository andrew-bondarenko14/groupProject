<template>
        <div class="settings_block">
            <ul class="main_settings">
                <li>Вход в аккаунт</li>
                <li style="padding-right: 25px;" @click="() => {closeAll();showChangePassword = true;}">
                    <div>Пароль:</div>
                    <span v-if="passwordLastChange !== 'Не было изменений'">{{ 'Последнее изменение ' + createDate(passwordLastChange) }}</span>
                    <span v-if="passwordLastChange === 'Не было изменений'">{{ 'Последнее изменение ' + passwordLastChange }}</span>
                    <span class="change_button_wrapper"><i class="change_button"></i></span>
                </li>
                <li>
                    <div>Двухэтапная аутентификация</div>
                    <div>
                    <toggle-button
                        v-model="twoStepAuth"
                        color="#1875F0"
                         :sync="true"
                        :labels="{checked: 'Включена', unchecked: 'Выключена'}"
                        :height="30"
                        :width="118"/>
                    </div>
                    <span @click.stop="showTwoStepAuth" class="info_button"></span>
                </li>
                <li>
                    <div>Резервный пароль</div>
                    <div>
                    <toggle-button
                        v-model="backupPassword"
                        color="#1875F0"
                        :sync="true"
                        :labels="{checked: 'Включена', unchecked: 'Выключена'}"
                        :height="30"
                        :width="118"/>
                    <span @click.stop="showBackupPassword" class="info_button"></span>
                    </div>
                </li>
                <li style="padding-right: 25px;" @click="() => {closeAll(); showChangeBackupEmail = true;}">
                    <div>Резервный email</div>
                    <span>{{ backupEmail }}</span>
                    <span class="change_button_wrapper"><i class="change_button"></i></span>
                </li>
            </ul>
            <ul class="extra_settings">
                <li>Дополнительные функции</li>
                <li>
                    <div>Уведомления о подозрительных входах</div>
                    <div>
                    <toggle-button
                                   v-model="securityNotifications"
                                   color="#1875F0"
                                   :sync="true"
                                   :labels="{checked: 'Включена', unchecked: 'Выключена'}"
                                   :height="30"
                                   :width="118"/>
                    <span @click.stop="showSecurityNotifications" class="info_button"></span>
                    </div>
                </li>
                <li>
                    <div>Кодовое слово при назначении пароля</div>
                    <div>
                    <toggle-button
                        @change="handleCodeword"
                        v-model="codewordSwitch"
                        color="#1875F0"
                        :sync="true"
                        :labels="{checked: 'Включена', unchecked: 'Выключена'}"
                        :height="30"
                        :width="118"/>
                    <span @click.stop="showCodeword" class="info_button"></span>
                    </div>
                </li>
            </ul>
            <div class="button_block">
                <button @click="saveSettings" type="button" class="btn btn-primary">Сохранить</button>
                <button @click="$router.push({name : 'main_page'})" type="button" class="btn btn-outline-secondary">Отмена</button>
            </div>
            <info-modal :title="modalTitle" :text="modalText" @close="showModal=false" :opened="showModal"></info-modal>
            <change-password-modal @close="handleDataUpdate"
                                   :opened="showChangePassword">
            </change-password-modal>
            <change-backup_email-modal
                @close="handleDataUpdate"
                :opened="showChangeBackupEmail"
                :email="email">
            </change-backup_email-modal>
            <codeword-modal :opened="showCodewordModal"
                            @setCodeword="updateCodeword($event)"
                            @close="showCodewordModal = false"></codeword-modal>
        </div>
</template>

<script>

    export default {
        data: function () {
            return {
                twoStepAuth: false,
                backupPassword: false,
                securityNotifications: false,
                passwordLastChange: '',
                codeword: '',
                codewordSwitch: false,
                showModal: false,
                modalText: '',
                modalTitle: '',
                showChangePassword: false,
                showChangeBackupEmail: false,
                showCodewordModal: false,
                email: '',
                backupEmail: ''
            }
        },
        created() {
            let self = this;
            axios.get('/api/user/security/settings')
                .then(function (response) {
                    let settings = response.data.settings;
                    self.backupEmail = settings.backup_email ?? "Не установлен";
                    self.twoStepAuth = !!settings.two_step_authentication;
                    self.backupPassword = !!settings.backup_password;
                    self.codewordSwitch = !!settings.codeword;
                    self.codeword = settings.codeword;
                    self.securityNotifications = !!settings.security_notifications;
                    self.passwordLastChange = settings.password_changed_at ?? 'Не было изменений';
                    self.email = response.data.email
                })
        },
        methods: {
            showTwoStepAuth() {
                this.closeAll();
                this.modalTitle = 'Двухэтапная аутентификация';
                this.modalText = 'После включения данной функции, каждый раз при авторизации на Ваш электронный адрес будет отправляться код подтверждения, с помощью которого Вы сможете подтверждать вход в систему.\n';
                this.showModal = true;
            },
            showBackupPassword() {
                this.closeAll();
                this.modalTitle = 'Резервный пароль';
                this.modalText = 'После включения данной функции на Ваш email будет отправлен резервный пароль  с помощью которого Вы сможете входить в систему,  если забудете основной пароль.\n';
                this.showModal = true;
            },
            showSecurityNotifications() {
                this.closeAll();
                this.modalTitle = 'Уведомления о подозрительных входах';
                this.modalText = 'После включения данной функции, система автоматически будет отправлять письмо с предупреждением об опасности на Ваш адрес электронной почты. \n\n *Под подозрительным входом имеется в виду вход в систему из браузера, который обычно не используется или же из другого города/страны.';
                this.showModal = true;
            },
            showCodeword() {
                this.closeAll();
                this.modalTitle = 'Кодовое слово при восстановлении пароля';
                this.modalText = 'После включения данной функции, система позволит изменить Ваш текущий пароль на новый без использования email(при активации функции “Забыли пароль”).';
                this.showModal = true;
            },
            handleDataUpdate() {
                this.showChangePassword = false;
                this.showChangeBackupEmail = false;
                let self = this;
                axios.get('/api/user/security/settings')
                    .then(function (response) {
                        let settings = response.data.settings;
                        self.codewordSwitch = !!settings.codeword;
                        self.backupEmail = settings.backup_email ?? "Не установлен";
                        self.twoStepAuth = !!settings.two_step_authentication;
                        self.backupPassword = !!settings.backup_password;
                        self.securityNotifications = !!settings.security_notifications;
                        self.passwordLastChange = settings.password_changed_at ?? 'Не было изменений';
                        self.email = response.data.email
                    });
            },
            saveSettings() {
                axios.post('/api/user/security/change/settings', {
                    'two_step_authentication': this.twoStepAuth,
                    'security_notifications': this.securityNotifications,
                    'backup_password': this.backupPassword,
                    'codeword': this.codeword,
                })
                    .then((response) => {
                        if (response.data === true) {
                            Vue.swal.fire(
                                'Изменение настоек',
                                'Ваши настройки были успешно изменены',
                                'success'
                            );
                        } else {
                            th.error = true;
                            th.errorText = response.data;
                        }
                    })
            },
            handleCodeword() {
                if (this.codewordSwitch == true) {
                    this.codewordSwitch = false;
                    this.closeAll();
                    this.showCodewordModal = true;
                } else {
                    this.codeword = '';
                    this.showCodewordModal = false;
                }
            },
            updateCodeword(event) {
                this.codeword = event;
                this.codewordSwitch = true;
                this.showCodewordModal = false;
            },
            closeAll() {
                this.showChangePassword = false;
                this.showChangeBackupEmail = false;
                this.showCodewordModal = false;
                this.showModal = false;

            },
            createDate(date) {
                return new Date(date).toLocaleString('ru-RU', {
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric'
                });
            }
        }
    }
</script>

<style scoped>
    ul {
        padding-inline-start: 40px;
    }
    .settings_block-wrapper {
        position: relative;
    }
    header,
    .settings_block{
        margin: 17px;
        padding: 10px;
        padding-bottom: 0;
        background: #FFFFFF;
        box-shadow: 0px 5px 30px rgba(0, 0, 0, 0.07);
        border-radius: 6px;
    }
    .settings_block {
        background: #FAFAFA;;
    }
    header h2 {
        font-weight: 300;
        font-size: 36px;
        line-height: 90px;
        color: #808080;
        margin-bottom: 20px;
    }
    .tabs {
        list-style: none;
        display: flex;
    }
    .tabs li {
        font-weight: 500;
        font-size: 14px;
        line-height: 20px;
        text-align: center;
        color: #B2B2B2;
        cursor: pointer;
        margin-right: 18px;
        padding-bottom: 30px;
        position: relative;
    }
    .tabs li:hover {
        color: #1875F0;
    }
    .tabs .active {
        border-bottom: 2px solid #1875F0;
        color: #1875F0;
    }
    .settings_block ul {
        list-style: none;
        background: #FFFFFF;
    }
    .settings_block ul li:first-of-type {
        font-weight: 500;
        font-size: 16px;
        line-height: 90px;
        color: #666666;
    }
    .settings_block ul li {
        border-bottom: 1.5px solid #F0F0F0;;
        font-weight: 300;
        font-style: normal;
        font-size: 14px;
        line-height: 60px;
        color: #666666;
        display: flex;
        align-items: center;
        flex-wrap: wrap;
        position: relative;
    }
    .settings_block ul li:last-of-type {
        border-bottom: none;
    }
    .settings_block ul div {
        max-width: 50%;
        margin: 0;
        min-width: 250px;
        flex-grow: 1;
    }
    .extra_settings {
    }
    .info_button {
        width:30px;
        height:30px;
        background-image: url('/storage/user_profile_icons/ic_help.svg');
        cursor: pointer;
        position: absolute;
        right: 2%;
        bottom: 0%;
        transform: translateY(-50%);
    }
    .vue-js-switch {
        margin: 0;
    }
    button {
        margin-bottom: 14px;
        margin-left: 10px;
        height: 50px;
        width: 120px;
    }
    .button_block {
        display: flex;
        justify-content: center;
    }
    .change_button {
        width:12px;
        height:12px;
        border-top: 2px solid #F0F0F0;
        border-right: 2px solid #F0F0F0;
        cursor: pointer;
        transform: rotate(45deg);
        display: inline-block;
    }

    .change_button_wrapper{
        width: 30px;
        height: 30px;
        cursor: pointer;
        position: absolute;
        right: 2%;
        bottom: 0%;
        transform: translateY(-50%);
        display: flex;
        justify-content: center;
        align-items: center;
    }
    @media(max-width:650px) {
        .extra_settings li > div:first-of-type {
            line-height: 30px;
        }
    }

</style>

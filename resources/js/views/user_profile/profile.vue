<template>
<div class="container">
    <div class="user-profile">
        <div class="main-title">Мой профиль</div>
        <form onsubmit="return false;" enctype="multipart/form-data">
            <div class="title">Общая информация</div>
            <div class="general">
                <div v-if="required_error" class="alert-danger text-center">Заполните, пожалуйста, все обязательные поля</div>
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1">
                        <div v-on:click="imageDownloadClick"
                             class="avatar-hover"
                             @mouseover="avatar_hover = true"
                             @mouseleave="avatar_hover = false">
                            <img v-if="avatar" :src="'/avatars/' + avatar" alt="Аватар профиля пользователя">
                            <div v-else class="initials">{{ initials }}</div>
                            <transition name="avatar-hover-transition">
                                <div v-show="avatar_hover" class="avatar-hover-message">Обновить фото</div>
                            </transition>
                        </div>
                        <div class="modal-mask">
                            <div class="image-select-area">
                                <vue-anka-cropper
                                    :options="{
                                        cropArea: 'circle',
                                        croppedHeight: 150,
                                        croppedWidth: 150,
                                        dropareaMessage: '',
                                    }"
                                    @cropper-saved="saveImage"
                                    @cropper-file-selected="showImageSelectArea"
                                    @cropper-error="imageDownloadError"
                                    @cropper-cancelled="imageCanceled">
                                </vue-anka-cropper>
                            </div>
                        </div>
                        <div v-if="avatar_error" class="error alert-danger">{{ avatar_error_text }}</div>
                    </div>
                    <div class="col-12 col-md-6 order-md-3">
                        <v-text-field
                            placeholder="Введите вашу фамилию"
                            v-model="surname"
                            label="Фамилия:"
                            :error-messages="surname_error_text"
                        ></v-text-field>
                    </div>
                    <div class="col-12 col-md-6 order-md-5">
                        <v-text-field
                            placeholder="Введите ваше имя"
                            v-model="name"
                            label="Имя:"
                            :error-messages="name_error_text"
                        ></v-text-field>
                    </div>
                    <div class="col-12 col-md-6 order-md-7">
                        <v-text-field
                            placeholder="Введите ваше отчество"
                            v-model="middle_name"
                            label="Отчество:"
                            :error-messages="middle_name_error_text"
                        ></v-text-field>
                    </div>
                    <div class="col-12 col-md-6 order-md-2">
                        <div class="edit_user_form edit_user_header_form">
                            <div>
                                <v-menu
                                    v-model="menu_birthday"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="290px">
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            placeholder="Выберите дату рождения"
                                            v-model="birthday"
                                            label="Дата рождения:"
                                            readonly
                                            :error-messages="birthday_error_text"
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker v-model="birthday"
                                                   @input="menu_birthday = false"
                                                   color="#1875F0"
                                                   locale="ru-RU">
                                    </v-date-picker>
                                </v-menu>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6 order-md-4">
                        <v-select v-model="department_id"
                                  placeholder="Временно не состоит в отделах"
                                  :items="departments"
                                  item-value="id"
                                  item-text="name"
                                  label="Отдел:">
                        </v-select>
                    </div>
                    <div class="col-12 col-md-6 order-md-6">
                        <v-text-field
                            placeholder="Введите вашу должность"
                            v-model="position"
                            label="Должность:"
                            :error-messages="position_error_text"
                        ></v-text-field>
                    </div>
                    <div class="col-12 col-md-6 order-md-8">
                        <div class="edit_user_form">
                            <div>
                                <v-menu
                                    v-model="menu_hired_at"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="290px">
                                    <template v-slot:activator="{ on }">
                                        <v-text-field
                                            placeholder="Выберите дату начала работы"
                                            v-model="hired_at"
                                            label="Дата начала работы:"
                                            readonly
                                            :error-messages="hired_at_error_text"
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker v-model="hired_at"
                                                   @input="menu_hired_at = false"
                                                   color="#1875F0"
                                                   locale="ru-RU">
                                    </v-date-picker>
                                </v-menu>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="title">Контактная информация</div>
            <div class="contacts">
                <div class="row">
                    <div class="col-12 col-md-6 order-md-1">
                        <v-text-field
                            placeholder="Введите ваш email"
                            v-model="email"
                            label="E-mail:"
                            :error-messages="email_error_text"
                        ></v-text-field>
                    </div>
                    <div class="col-12 col-md-6 order-md-3">
                        <v-text-field
                            placeholder="Введите ваш skype"
                            v-model="skype_login"
                            label="Skype:"
                            :error-messages="skype_login_error_text"
                        ></v-text-field>
                    </div>
                    <div class="col-12 col-md-6 order-md-2">
                        <v-text-field
                            placeholder="Введите ваш мобильныйтелефон"
                            v-model="mobile_phone"
                            label="Мобильный телефон:"
                            :error-messages="mobile_phone_error_text"
                        ></v-text-field>
                    </div>
                    <div class="col-12 col-md-6 order-md-4">
                        <v-text-field
                            placeholder="Введите ваш рабочий телефон"
                            v-model="work_phone"
                            label="Рабочий телефон:"
                            :error-messages="work_phone_error_text"
                        ></v-text-field>
                    </div>
                </div>
            </div>
            <div class="buttons text-center">
                <button class="button-save" v-on:click="sendData">Сохранить</button>
                <button class="button-cancel" v-on:click="reGetProfile">Отменить</button>
            </div>
        </form>
    </div>
</div>
</template>

<script>
    import vueAnkaCropper from 'vue-anka-cropper'
    const data = new FormData();
    export default {
        components: {vueAnkaCropper},
        name: "profile",
        data() {
            return {
                menu_birthday: '',
                menu_hired_at: '',
                profile_data: [],
                surname: '',
                name: '',
                middle_name: '',
                birthday: '',
                department_id: '',
                position: '',
                hired_at: '',
                email: '',
                skype_login: '',
                mobile_phone: '',
                work_phone: '',
                avatar: '',
                initials: '',
                departments: [],
                required_error: false,
                avatar_error: false,
                avatar_error_text: '',
                name_error_text: '',
                surname_error_text: '',
                birthday_error_text: '',
                position_error_text: '',
                email_error_text: '',
                mobile_phone_error_text: '',
                work_phone_error_text: '',
                middle_name_error_text: '',
                hired_at_error_text: '',
                skype_login_error_text: '',
                image_file: '',
                avatar_hover: false,
            };
        },
        methods: {
            imageDownloadError() {
                alert('Произошла ошибка при загрузке файла, поддерживаемые форматы: PNG, JPEG, JPG');
            },
            imageDownloadClick() {
                document.querySelector('.ankaCropper__selectButton').click();
            },
            showImageSelectArea() {
                document.querySelector('.modal-mask').style.zIndex = 1000;
                document.querySelector('.modal-mask').style.opacity = 1;
            },
            imageCanceled() {
                document.querySelector('.modal-mask').style.opacity = 0;
                document.querySelector('.modal-mask').style.zIndex = -1000;
            },
            saveImage(cropData) {
                const datafile = new FormData();
                document.querySelector('.modal-mask').style.opacity = 0;
                document.querySelector('.modal-mask').style.zIndex = -1000;
                this.avatar_error = false;
                this.image_file = cropData.croppedFile;
                if (this.image_file) {
                    datafile.append('id', this.profile_data.id);
                    datafile.append('file', this.image_file);
                    axios.post('/api/profile/save/avatar', datafile)
                        .then(response => {
                            if (response.data === true) {
                                this.image_file = '';
                                axios.get('/api/user')
                                    .then(response => {
                                        this.avatar = response.data.avatar_url;
                                        this.$eventBus.$emit('avatar_changed',this.avatar);

                                    });
                                alert('Данные успешно сохранены');
                            }
                        })
                        .catch(error => {
                            if (error.response.data.errors) {
                                if (error.response.data.errors.file) {
                                    this.avatar_error = true;
                                    this.avatar_error_text = error.response.data.errors.file[0];
                                }
                            }
                        });
                }
            },
            resetErrors() {
                this.required_error = false;
                this.name_error_text = '';
                this.surname_error_text = '';
                this.birthday_error_text = '';
                this.position_error_text = '';
                this.email_error_text = '';
                this.mobile_phone_error_text = '';
                this.work_phone_error_text = '';
                this.middle_name_error_text = '';
                this.hired_at_error_text = '';
            },
            reGetProfile() {
                this.image_file = '';
                this.avatar_error = false;
                this.resetErrors();
                this.getProfile();
            },
            getProfile() {
                axios.get('/api/user')
                    .then(response => {
                        this.profile_data = response.data;
                        this.surname = this.profile_data.surname;
                        this.name = this.profile_data.name;
                        this.middle_name = this.profile_data.middle_name;
                        this.birthday = this.profile_data.birthday;
                        this.department_id = this.profile_data.department_id;
                        this.position = this.profile_data.position;
                        this.hired_at = this.profile_data.hired_at;
                        this.email = this.profile_data.email;
                        this.skype_login = this.profile_data.skype_login;
                        this.mobile_phone = this.profile_data.mobile_phone;
                        this.work_phone = this.profile_data.work_phone;
                        this.avatar = this.profile_data.avatar_url;
                        this.getInitials();
                    });
            },
            getDepartments() {
                axios.get('/api/dashboard/departments')
                    .then(response => {
                        this.departments = response.data;
                    });

            },
            getInitials() {
                if (!this.avatar) {
                    this.initials = this.name[0] + this.surname[0];
                    this.initials.toUpperCase();
                }
            },
            sendData() {
                this.resetErrors();
                const data = new FormData();
                data.append('id', this.profile_data.id);
                data.append('department_id', this.department_id);
                data.append('position', this.position);
                data.append('surname', this.surname);
                data.append('name', this.name);
                data.append('middle_name', this.middle_name);
                data.append('birthday', this.birthday);
                data.append('hired_at', this.hired_at);
                data.append('email', this.email);
                if (this.mobile_phone) {
                    data.append('mobile_phone', this.mobile_phone);
                }
                if (this.work_phone) {
                    data.append('work_phone', this.work_phone);
                }
                data.append('skype_login', this.skype_login);
                axios.post('/api/profile/save/data', data)
                    .then(response => {
                        if (response.data === true) {
                            alert('Данные успешно сохранены');
                        }
                    })
                    .catch(error => {
                        if (error.response.data.errors) {
                            let check=false;
                            $.each(error.response.data.errors, function(key, value) {
                                if (value == 'Поле обязательно к заполнению') {
                                    check = true;
                                }
                            });
                            this.required_error = check;
                            if (error.response.data.errors.name) {
                                this.name_error_text = error.response.data.errors.name[0];
                            }
                            if (error.response.data.errors.surname) {
                                this.surname_error_text = error.response.data.errors.surname[0];
                            }
                            if (error.response.data.errors.birthday) {
                                this.birthday_error_text = error.response.data.errors.birthday[0];
                            }
                            if (error.response.data.errors.position) {
                                this.position_error_text = error.response.data.errors.position[0];
                            }
                            if (error.response.data.errors.email) {
                                this.email_error_text = error.response.data.errors.email[0];
                            }
                            if (error.response.data.errors.mobile_phone) {
                                this.mobile_phone_error_text = error.response.data.errors.mobile_phone[0];
                            }
                            if (error.response.data.errors.work_phone) {
                                this.work_phone_error_text = error.response.data.errors.work_phone[0];
                            }
                            if (error.response.data.errors.middle_name) {
                                this.middle_name_error_text = error.response.data.errors.middle_name[0];
                            }
                            if (error.response.data.errors.hired_at) {
                                this.hired_at_error_text = error.response.data.errors.hired_at[0];
                            }
                            if (error.response.data.errors.skype_login) {
                                this.skype_login_error_text = error.response.data.errors.skype_login[0];
                            }
                        }
                    });
            }
        },
        created() {
            this.getProfile();
            this.getDepartments();
        }
    }
</script>

<style scoped>
    .edit_user_form, .edit_user_form input{
        font-weight: 500;
        font-size: 14px;
        color: #666;
    }
    .edit_user_form input {
        margin: 10px 0 17px 0;
        line-height: 60px;
        width: 80%;
        border: 2px solid #F5F5F5;
        border-radius: 4px;
    }
    .edit_user_form span {
        line-height: 30px;
    }
    .edit_user_header_form {
        margin-top: 60px;
    }
    .user-profile div, .user-profile input, .user-profile button, .user-profile select {
        font-family: Roboto;
        font-style: normal;
    }
    .user-profile input, .user-profile select, .main-title, .general, .contacts, .title, .buttons {
        background-color: #FFFFFF;
    }
    .user-profile div {
        font-weight: 500;
        font-size: 14px;
        line-height: 30px;
        color: #666666;
    }
    .user-profile input, .user-profile select {
        font-weight: bold;
        font-size: 14px;
        line-height: 60px;
    }
    .user-profile select {
        height: 60px;
    }
    .user-profile button {
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
    .buttons {
        padding-top: 50px;
        padding-bottom: 40px;
    }
    .button-save {
        color: #FFFFFF;
        background-color: #1875F0;
        border: 2px;
    }
    .button-save:hover {
        background: #1855F0;
        transition: 0.5s;
    }
    .button-cancel {
        color: #B2B2B2;
        background-color: #FFFFFF;
        border: 2px solid #F5F5F5;
    }
    .button-cancel:hover {
        color: #FFFFFF;
        background: #B2B2B2;
        transition: 0.5s;
    }
    .user-profile .general, .user-profile .contacts, .user-profile .title {
        padding: 0 80px;
    }
    .user-profile .main-title {
        font-weight: 300;
        font-size: 36px;
        line-height: 90px;
        color: #808080;
        padding: 5px 14px 17px;
        margin-bottom: 10px;
        margin-top: 15px;
    }
    .user-profile .title {
        font-size: 18px;
        line-height: 90px;
        text-align: center;
        color: #999999;
        margin-bottom: 2px;
    }
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
    .image-select-area {
        width: 1200px;
        max-height: 1200px;
        position: fixed;
        left: 50%;
        top: 50%;
        margin-left: -500px;
        margin-top: -300px;
    }
    .avatar-hover {
        cursor: pointer;
        margin: 0 auto;
        width: 150px;
        height: 150px;
        overflow: hidden;
        border: 1px solid #666;
        border-radius: 50%;
    }
    .avatar-hover img {
        transition: filter 0.5s ease;
    }
    .avatar-hover:hover img{
        filter: brightness(40%);
    }
    .initials {
        text-align: center;
        line-height: 150px;
        font-size: 32px;
        color: #999;
    }
    .avatar-hover-message {
        opacity: 0.7;
        z-index: 100;
        text-align: center;
        position: absolute;
        border-radius: 20px;
        width: 140px;
        margin-left: -70px;
        margin-top: -15px;
        left: 50%;
        top: 50%;
        background-color: #fff;
    }
    .avatar-hover-transition-enter-active, .avatar-hover-transition-leave-active {
        transition: opacity 0.5s;
    }
    .avatar-hover-transition-enter, .avatar-hover-transition-leave-to {
        opacity: 0;
    }
    .error {
        padding-left: 10px;
        width: 90%;
    }
    .user-profile /deep/ label {
        margin-top: 0;
        color: #666666;
    }
    .user-profile /deep/ .theme--light.v-input input {
        color: #808080;
    }
    .user-profile /deep/ .theme--light.v-messages {
        color: #FF0000;
    }
    .user-profile /deep/ label {
        margin-bottom: 0!important;
    }
    .user-profile /deep/ .v-select__selection{
        color: #808080;
    }
    .user-profile /deep/ .v-text-field .v-label--active {
        max-width: 133%;
        transform: translateY(-18px) scale(1,1);
        font-size: 12px;
    }
    @media screen and (max-width: 1140px) {
        .image-select-area {
            width: 900px;
            max-height: 900px;
            margin-left: -350px;
        }
    }
    @media screen and (max-width: 960px) {
        .image-select-area {
            width: 560px;
            max-height: 560px;
            margin-left: -280px;
        }
    }
    @media screen and (max-width: 720px) {
        .main-title {
            font-size: 32px;
            line-height: 45px;
        }
        .general, .contacts, .title {
            padding: 0 40px;
        }
        .title {
            line-height: 45px;
            padding-top: 15px;
        }
        .image-select-area {
            width: 460px;
            max-height: 460px;
            margin-left: -230px;
        }
    }
    @media screen and (max-width: 540px) {
        .image-select-area {
            width: 320px;
            margin-left: -150px;
            margin-top: -260px;
        }
        .general, .contacts, .title {
            padding: 0 20px;
        }
        .user-profile button {
            width: 90px;
            margin: 0 10px;
        }
        .buttons {
            padding-top: 30px;
            padding-bottom: 20px;
        }
    }
    @media screen and (max-width: 270px) {
        .user-profile button {
            width: 80px;
            margin: 0 5px ;
        }
    }
</style>
<style>
    .image-select-area .ankaCropper__selectButton {
        display: none;
    }
</style>

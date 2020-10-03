<template>
    <div class="settings_block">

    <form>
        <div class="form-group">
        <label>Диск:</label>
        <v-select
            v-model="accesses.disk"
            :items="items"
            outlined
        ></v-select>
        </div>
        <div class="form-group">
            <label>Почта:</label>
            <v-select
                v-model="accesses.mail"
                :items="items"
                outlined
            ></v-select>
        </div>
        <div class="form-group">
            <label>Календарь:</label>
            <v-select
                v-model="accesses.calendar"
                :items="items"
                outlined
            ></v-select>
        </div>
        <div class="form-group">
            <label>Фотографии:</label>
            <v-select
                v-model="accesses.photos"
                :items="items"
                outlined
            ></v-select>
        </div>
        <div class="form-group">
            <label>Контакты:</label>
            <v-select
                v-model="accesses.contacts"
                :items="items"
                outlined
            ></v-select>
        </div>
    </form>
        <div class="button_block">
            <button @click="save"  type="button" class="btn btn-primary">Подтвердить</button>
            <button @click="$router.push({name : 'dashboardUsers'})" type="button" class="btn btn-outline-secondary">Отмена</button>
        </div>
    </div>
</template>

<script>
    export default {
        data: function () {
            return {
                accesses: {
                    disk: true,
                    mail: true,
                    calendar: true,
                    photos: true,
                    contacts: true,
                },
                user: '',
                items: [{text: 'Разрешен', value: 1},{text: 'Запрещен',value: 0}]
            }
        },
        created() {
            let self = this;
                axios.get(`/api/dashboard/user/${this.$route.params.id}/edit`)
                    .then(function(response) {
                        self.user = response.data[0];
                      self.accesses = response.data[0].accesses;
                    });
        },
        methods: {
            save() {
                this.user.accesses = this.accesses;
                axios.put(`/api/dashboard/user/${this.$route.params.id}`, this.user)
                .then(function () {
                    Vue.swal.fire(
                        'Изменение доступов пользователя',
                        'Доступы пользователя были успешно изменены',
                        'success'
                    );
                })
                .error(function () {
                    Vue.swal.fire({
                        icon: 'error',
                        title: 'Изменение доступов пользователя',
                        text: 'При изменении доступов пользователя произошла ошибка, попробуйте еще раз',
                    });
                })
                },
            }
    }
</script>

<style scoped>
    .v-select__selection--comma {
        color: #808080!important;
    }
    label {
        font-style: normal;
        font-weight: 500;
        font-size: 14px;
        line-height: 30px;
        color: #666666;
        margin-bottom: 20px;
        margin-top: 12px;
    }
    .v-input {
        font-style: normal;
        font-weight: bold;
        font-size: 14px;
        color: #808080!important;
        height: 60px;
    }
   .v-input__slot {
       border: 2px solid #F5F5F5;
    outline: none;
   }
    .v-overflow-btn .v-input__slot::before {
        border-color: grey !important;
    }
    .form-group {
        max-width: 380px;
        margin: auto;
    }
    .v-text-field--outlined fieldset {
        border-radius: 4px;
        border: 2px solid #F5F5F5;
    }
    .v-input--is-focused fieldset {
        border-radius: 4px;
        border: 2px solid #F5F5F5!important;
    }
    .v-input__control, .v-input__slot, .v-select__slot {
        border: 0!important;
    }

    .v-list-item__content, .v-select__selection,.v-list-item__title{
        font-style: normal;
        font-weight: bold;
        font-size: 14px;
        color: #808080!important;
    }
    .v-list, .v-select-list {
        padding: 0;
    }
    .button_block {
        display: flex;
        justify-content: center;
        margin-top: 30px;
    }
    button {
        margin-bottom: 14px;
        margin-left: 10px;
        height: 50px;
        width: 120px;
    }
    .form-group /deep/ .v-select__selection--comma {
        color: #808080!important;
    }
</style>

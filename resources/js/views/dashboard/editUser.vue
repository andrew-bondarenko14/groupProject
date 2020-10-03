<template>
    <div class="settings_block-wrapper">
        <header>
            <h2>Редактирование пользователя</h2>
            <ul class="tabs">
                <li class="active"><router-link exact :to="{name: 'editUser_general'}">Персональные данные</router-link></li>
                <li><router-link exact :to="{name: 'editUser_accesses'}">Доступы</router-link></li>
            </ul>
        </header>
        <div>
            <router-view></router-view>
        </div>
    </div>
</template>

<script>
    export default {

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
    .tabs li a {
        font-weight: 500;
        font-size: 14px;
        line-height: 20px;
        text-align: center;
        color: #B2B2B2;
        cursor: pointer;
        margin-right: 18px;
        padding-bottom: 30px;
        position: relative;
        text-decoration: none;
    }
    .tabs li:hover a {
        color: #1875F0;
    }
    .router-link-active{
        display: block;
        margin-top: 2px;
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
    }
    .settings_block ul li:last-of-type {
        border-bottom: none;
    }
    .settings_block ul div {
        max-width: 50%;
        margin: 0;
        min-width: fit-content;
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
        right: 50px;
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
        position: absolute;
        right: 63px;
        transform: rotate(45deg);
    }
    @media(max-width : 845px) {
        /*.settings_block ul li {*/
        /*    flex-direction: column;*/
        /*    align-items: start;*/
        /*    padding-bottom:10px;*/
        /*}*/
    }
</style>

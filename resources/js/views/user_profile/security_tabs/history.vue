<template>
        <div class="settings_block">
            <ul v-show="showActions || recentActions.length == 0" class="main_settings">
                <li>Недавние действия <button class="btn btn-primary" @click="clearHistory('actions')">Очистить</button></li>
                <li v-for="action in paginateActions(recentActions)">
                    <div>{{ action.action }}</div>
                    <div>{{ createReport(action) }}</div>
                </li>
                <li v-if="recentActions.length == 0">Вы еще не совершали изменения</li>
            </ul>
            <ul v-show="showLogins || loginHistory.length == 0" class="extra_settings">
                <li>История посещений <button class="btn btn-primary" @click="clearHistory('logins')">Очистить</button></li>
                <li class="day_block" v-for="day in paginateHistory(loginHistory)">
                    <div class="block_date">{{ createDate(day.date) }}</div>
                    <ul class="day_actions-block" v-for="(action) in day.actions">
                        <li>
                            <img v-if="action.actionCode == 0" src="/storage/user_profile_icons/ic_laptop.png" alt="turn_on_ic">
                            <img v-else src="/storage/user_profile_icons/ic_turn_off.png" alt="turn_off_ic">
                            {{ action.action }}
                            <span v-if="action.currentSession">(текущий сеанс)</span>
                        </li>
                        <li>{{ createReport(action) }}</li>
                    </ul>
                </li>
                <li class="empty_notification" v-if="loginHistory.length == 0">Здесь пока нет записей</li>
            </ul>
           <pagination-links v-show="getPaginationPages() > 0" @pageChanged="setPage($event)" :pagesCount="getPaginationPages()"></pagination-links>
        </div>
</template>
<script>

    export default {
        data: function () {
            return {
                loginHistory: [],
                recentActions: [],
                currentPage: 1,
                showLogins: true,
                showActions: true
            }
        },
        created: function () {

            let self = this;
            axios.get('/api/user/security/logs')
                .then(function (response) {
                    let currentSessionFound = false
                    response.data.logs.forEach((log) => {
                        if (log.action == 'Вход в систему' || log.action == 'Выход из системы') {
                            if (log.action == 'Вход в систему' && !currentSessionFound) {
                                log['currentSession'] = true;
                                currentSessionFound = true;
                            }
                            log['actionCode'] = log.action == 'Вход в систему' ? 0 : 1;
                            self.loginHistory.push(log);
                        } else
                            self.recentActions.push(log);
                    });

                })
        },
        methods: {
            createReport(log) {

                return log.location + ' – ' + new Date(log.created_at).toLocaleString('ru-RU', {
                    month: 'long',
                    day: 'numeric',
                    time: 'numeric',
                    hour: 'numeric',
                    minute: 'numeric',

                });
            },
            createDate(date) {

                return new Date(date).toLocaleString('ru-RU', {
                    month: 'long',
                    day: 'numeric',
                    year: 'numeric'

                });
            },
            getPaginationPages() {
                let loginHistory = Math.ceil(this.loginHistory.length / 19);
                let actionHistory = Math.ceil(this.recentActions.length / 10);
                return loginHistory > actionHistory ? loginHistory : actionHistory;
            },
            paginateActions(actions) {
                let toShow = actions.slice((this.currentPage - 1) * 10, (this.currentPage - 1) * 10 + 10);
                this.showActions = toShow.length >= 1 ? true : false;
                return toShow;
            },
            paginateHistory(history) {
                let toShow = history.slice((this.currentPage - 1) * 19, (this.currentPage - 1) * 19 + 19);
                this.showLogins = toShow.length >= 1 ? true : false;
                const groups = toShow.reduce((groups, record) => {
                    const date = record.created_at.split('T')[0];
                    if (!groups[date]) {
                        groups[date] = [];
                    }
                    groups[date].push(record);
                    return groups;
                }, {});
                const groupArrays = Object.keys(groups).map((date) => {
                    return {
                        date,
                        actions: groups[date]
                    };
                });

                toShow = groupArrays;
                return toShow;
            },
            setPage(event) {
                this.currentPage = event;
            },
            clearHistory(toClear) {
                let self = this;
                axios.post('/api/user/security/logs/clear',{'toDelete' : toClear})
                .then(function () {
                axios.get('/api/user/security/logs')
                    .then(function (response) {
                        let currentSessionFound = false;
                        self.loginHistory = [];
                        self.recentActions = [];
                        response.data.logs.forEach((log) => {
                            if (log.action == 'Вход в систему' || log.action == 'Выход из системы') {
                                if (log.action == 'Вход в систему' && !currentSessionFound) {
                                    log['currentSession'] = true;
                                    currentSessionFound = true;
                                }
                                log['actionCode'] = log.action == 'Вход в систему' ? 0 : 1;
                                self.loginHistory.push(log);
                            } else
                                self.recentActions.push(log);
                        });
                        self.$forceUpdate();
                    })
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
    .settings_block > ul > li:first-of-type {
        font-weight: 500;
        font-size: 16px;
        line-height: 90px;
        color: #666666;
    }
    .settings_block  .main_settings >  li
    {
        border-bottom: 1.5px solid #F0F0F0;;
        font-weight: 300;
        font-style: normal;
        font-size: 13px;
        line-height: 60px;
        color: #666666;
        display: flex;
        align-items: center;
        width: 100%;
        flex-wrap: wrap;
        padding-right: 15px;
    }
    .settings_block .main_settings li, .empty_notification
    {
        font-weight: 300;
        font-style: normal;
        font-size: 13px;
        line-height: 60px;
        color: #666666;
        display: flex;
        align-items: center;
        width: 100%;
    }
    .empty_notification {
        border-top: 1.5px solid #F0F0F0;;
    }
    .settings_block .main_settings li:last-of-type {
        border-bottom: none;
    }
    .settings_block .main_settings div {
        max-width: 50%;
        margin: 0;
        min-width: fit-content;
        flex-grow: 1;
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
    .day_block {
        display: flex;
        flex-direction: column;
    }
    .day_block > div {
        border-top : 1px solid #F0F0F0;
        font-style: normal;
        font-weight: normal;
        font-size: 13px;
        line-height: 60px;
        color: #666666;
    }

    .day_block > ul {
        display: flex;
        padding: 0;
    }
    .day_block > ul > li:last-of-type {
        padding-right: 15px;
    }
    .day_block > ul li {
        flex-grow: 1;
        max-width: 50%;
        font-style: normal;
        font-weight: 300;
        font-size: 13px;
        line-height: 60px;
        color: #666666;
    }
    .day_actions-block:first-of-type {
        border-top : 1px solid #F0F0F0;
    }
    .settings_block ul > li:first-of-type button {
        max-width: 120px!important;
        max-height: 38px;
    }

    @media(max-width:650px) {
        .day_block > ul,
        .main_settings > li{
            flex-direction: column;
        }
        .main_settings > li {
            align-items: flex-start;
        }
        .main_settings li  div {
            width: 100%;
            max-width: 100%!important;
        }
        .day_block > ul li,
        .main_settings li  div{
            max-width: 100%;
            line-height: 30px;
            padding-bottom: 15px;
        }
        .settings_block > ul > li:first-of-type {
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }
        .settings_block ul {
            padding-left: 15px;
        }
    }
</style>

<template>
    <div>
        <div class="welcome_block">
            <carousel
                :loop="true"
                :autoplay="true"
                :autoplayTimeout="2000"
                :paginationEnabled="false"
                :mouseDrag="false"
                :perPage="1">
                <slide class="slide">
                    <div class="img"></div>
                </slide>
                <slide class="slide">
                    <div class="img"></div>
                </slide>
                <slide class="slide">
                    <div class="img"></div>
                </slide>
            </carousel>
            <div class="greeting_block">
                <h2>Рады снова Вас приветствовать!</h2>
                <p>Пожалуйста, обратите внимание на новый функционал:</p>
            </div>
        </div>
        <div class="functionality_block">
            <ul class="row functional_wrapper">
                    <router-link tag="li" class="col-lg-6 col-md-12" to="/disk">
                        <div class="functionality_img-block">
                            <img src="/storage/main_page/functionality_ic/flag_icon.png">
                        </div>
                        <div>
                        <h4>Храните важные документы на диске!</h4>
                        <p>Ваше хранилище Disk позволяет хранить до 15 ГБ  документов,  необходимых для работы.</p>
                        <span>Занято {{ diskSpaceUsed }} ГБ из {{ diskSpaceAvailiable }} ГБ ({{ diskSpaceLeft }}%)</span>
                        </div>
                    </router-link>

                    <router-link tag="li" class="col-lg-6 col-md-12" to="/mail">
                        <div class="functionality_img-block">
                            <img src="/storage/main_page/functionality_ic/cup_icon.png">
                        </div>
                        <div>
                            <h4> Ускорьте скорость доставки писем!</h4>
                            <p>Используйте инструмент Mail для внутреннего комфортного общения среди сотрудников компании.</p>
                            <span>Без ограничения на количество писем.</span>
                        </div>
                    </router-link>

                    <router-link tag="li" class="col-lg-6 col-md-12" to="/calendar">
                        <div class="functionality_img-block">
                            <img src="/storage/main_page/functionality_ic/wallet_icon.png">
                        </div>
                        <div>
                            <h4>Записывайте важные даты!</h4>
                            <p> Используйте удобный инструмент Calendar для фиксации важных событий, встреч и рабочих мероприятий.</p>
                            <span>Оцените интуитивно понятный интерфейс и локаничный дизайн. </span>
                        </div>
                    </router-link>
                    <router-link tag="li" class="col-lg-6 col-md-12" to="/photos">
                        <div class="functionality_img-block">
                            <img src="/storage/main_page/functionality_ic/cup_icon.png">
                        </div>
                        <div>
                        <h4>Делитесь воспоминания с коллегами!</h4>
                        <p>Публикуйте все знаменательные события из жизни компании в личное пространство Photos.</p>
                        <span>Формируйте папки и вложенность как удобно Вам.</span>
                        </div>
                    </router-link>
                    <router-link tag="li" class="col-lg-6 col-md-12" to="/contacts">
                        <div class="functionality_img-block">
                            <img src="/storage/main_page/functionality_ic/cup_icon.png">
                        </div>
                        <div>
                        <h4>Сохраняйте больше контактов!</h4>
                        <p>Добавляйте важные номера телефонов, адреса электронных  почт, никнеймы различных мессенджеров в одно место Contacts.</p>
                        <span>Вперёд за возможностями!</span>
                        </div>
                    </router-link>
            </ul>
        </div>
    </div>
</template>

<script>
    import { Carousel, Slide } from 'vue-carousel';
    export default {
        components: {
            Carousel,
            Slide
        },
        data : function () {
            return {
                diskSpaceLeft : '',
                diskSpaceUsed : '',
                diskSpaceAvailiable : '',
            }
        },
        created() {
            let self = this;
            self.diskSpaceUsed = 0;
            self.diskSpaceAvailiable = 15;
            self.diskSpaceLeft = (self.diskSpaceUsed/self.diskSpaceAvailiable * 100).toFixed(2);
            // })
            // axios.get('/diskSpace')
            // .then(function (response) {
            //     self.diskSpaceUsed = response.diskSpaceUsed ?? 0;
            //     self.diskSpaceAvailiable = response.diskSpaceAvailiable ?? 15;
            //     self.diskSpaceLeft = (self.diskSpaceUsed/self.diskSpaceAvailiable * 100).toFixed(2);
            // })
        }
    }
</script>

<style scoped>
    .VueCarousel .img {
        width: 100%;
        height: 300px;
        background-size: cover;
        background-position: center;
    }
    .VueCarousel .slide:nth-child(1) .img {
        background-image: url("/storage/main_page/slider_img/slider_1.png");
    }
    .VueCarousel .slide:nth-child(2) .img {
        background-image: url("/storage/main_page/slider_img/slider_2.png");
    }
    .VueCarousel .slide:nth-child(3) .img{
        background-image: url("/storage/main_page/slider_img/slider_3.png");
    }
    .greeting_block {
        background: #FFFFFF;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.05);
        border-radius: 6px;
        max-width: 1110px;
        margin: auto;
        height: 143px;
        display: flex;
        flex-direction: column;
        justify-content : center;
        position: relative;
        top: -40px
    }
    .greeting_block h2 {
        font-style: normal;
        font-size: 36px;
        line-height: 30px;
        text-align: center;
        color: #666666;
    }
    .greeting_block p {
        font-style: normal;
        font-weight: normal;
        font-size: 24px;
        line-height: 30px;
        text-align: center;
        color: #666666;
    }
    .functionality_block {
        max-width: 1110px;
        margin:auto;
    }
    .functional_wrapper {
        list-style: none;
        padding: 0;
        width: 100%;
        display:flex;
        justify-content: center;
        margin:0;
    }
    .functional_wrapper li {
        margin-bottom: 30px;
        display: flex;
        justify-content: center;
        background: #FFFFFF;
        box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.05);
        border-radius: 6px;
        max-width:540px;
        padding: 0;
    }
    .functional_wrapper li:nth-child(odd) {
        margin-right: 25px;
    }

    .functional_wrapper li div {

        padding: 61px 10px 5px 0;
        max-width:327px;
        min-height: 240px;
        box-sizing: content-box;
    }
    .functional_wrapper li .functionality_img-block {
        width: 200px;
        display: flex;
        align-items: center;
        justify-content: center;
        padding:0;
        background-repeat: no-repeat;
        background-position: center;
    }
    .functional_wrapper .functionality_img-block img {
        position: relative;
        left: -35px;
    }
    .functional_wrapper li:nth-child(4) .functionality_img-block,
    .functional_wrapper li:nth-child(5) .functionality_img-block {
        background-image: url("/storage/main_page/functionality_ic/B.png");
    }
    .functional_wrapper li:nth-child(1) .functionality_img-block {
        background-image: url("/storage/main_page/functionality_ic/D.png");
    }
    .functional_wrapper li:nth-child(2) .functionality_img-block {
        background-image: url("/storage/main_page/functionality_ic/A.png");
    }
    .functional_wrapper li:nth-child(3) .functionality_img-block {
    background-image: url("/storage/main_page/functionality_ic/S.png");
    }
    .functional_wrapper h4 {
        font-weight: 500;
        font-size: 18px;
        line-height: 30px;
        cursor: pointer;
        color: #666666;
    }
    .functional_wrapper p,
    .functional_wrapper span
    {
        font-weight: 500;
        font-size: 12px;
        line-height: 25px;
        color: #B3B3B3;
    }
    .functional_wrapper span
    {
        cursor: pointer;
        position: absolute;
        max-width: 327px;
        bottom : 10px;
    }
    @media  (max-width: 1270px) {
        .functional_wrapper li {
            margin-right:0!important;
            flex: 0 0 100%!important;
            width: 100%;
        }

        .greeting_block {
            margin: 0 10px;
        }
        .greeting_block h2 {
        }
        .greeting_block p {
        }
    }

    @media  (max-width: 700px) {

        .functional_wrapper li {
            padding-right:10px;
            padding-left:10px;
        }
        .greeting_block h2 {
            font-size: 5vw;
        }
        .greeting_block p {
            font-size: 4vw;
        }
    }
</style>

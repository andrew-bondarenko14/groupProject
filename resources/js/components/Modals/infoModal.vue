<template>
        <div class="modal_body"
             v-click-outside="close"
             :style="opened ? 'top: ' + windowHeight/2 + 'px;' : ''">
            <h4>{{ title }}</h4>
            <p>{{ text }}</p>
            <span @click="close" class="close"></span>
        </div>
</template>

<script>
    export default {
        props : ['title', 'text','opened'],
        data : function() {
          return {
              windowHeight : 0,
          }
        },
        methods: {
            close() {
                this.$emit('close');
            },
            onResize() {
                this.windowHeight = window.innerHeight
            }

        },
        mounted() {
                window.addEventListener('resize', this.onResize);
                this.onResize();
        },
        beforeDestroy() {
            window.removeEventListener('resize', this.onResize);
        },

    }
</script>
<style scoped>

    .modal_body {
        position:fixed;
        left: 50%;
        top: -50%;
        transform: translate(-50%,-50%);
        transition: top .5s ease-in-out;
        background: #FFFFFF;
        box-shadow: 0px 5px 30px rgba(0, 0, 0, 0.07);
        border-radius: 6px;
        width: 100%;
        max-width: 540px;
        padding: 18px 10px 10px 45px;
        z-index: 100;
    }
    .modal_body h4 {
        font-style: normal;
        font-weight: 500;
        font-size: 18px;
        line-height: 30px;
        color: #666666;
    }

    .modal_body p {
        font-style: normal;
        font-weight: normal;
        font-size: 12px;
        line-height: 20px;
        color: #666666;
        white-space: pre-line;
    }
    .close {
        position: absolute;
        right: 15px;
        top: 10px;
        width: 26px;
        height: 26px;
        opacity: 0.3;
    }
    .close:hover {
        opacity: 1;
    }
    .close:before, .close:after {
        position: absolute;
        left: 15px;
        content: ' ';
        height: 26px;
        width: 2px;
        background-color: #333;
    }
    .close:before {
        transform: rotate(45deg);
    }
    .close:after {
        transform: rotate(-45deg);
    }

    .modal_opened {
        top: 50%;
    }


</style>

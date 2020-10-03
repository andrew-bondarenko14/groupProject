<template>
    <label>
        <input @input="handleInput($event)" v-model="content" placeholder=" " required :name="name" :type="inputType" :disabled="disabled">
        <span>{{ label }}</span>
        <span  @mousedown="togglePassword('confirm')" @mouseup="togglePassword('confirm')"
              @mouseleave="inputType='password'" v-if="!isMobile && (content.length > 0) && (type=='password')" class="show_password"></span>
        <span  @click="togglePassword" v-if="isMobile && (content.length > 0) && (type=='password')" class="show_password"></span>
    </label>
</template>

<script >
    export default {
        props: ['label', 'value', 'type', 'disabled', 'name'],
        data: function () {
            return {
                content: this.value,
                inputType: this.type
            }
        },
        watch: {
            value: function () {
                this.content = this.value;
            }
        },
        methods: {
            handleInput(e) {
                this.$emit('input', this.content)
            },
            togglePassword() {
                if (this.inputType == 'password')
                    this.inputType = 'text';
                else
                    this.inputType = 'password';
            },
            isMobile() {
                if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
                    return true
                } else {
                    return false
                }
            }
        }
    }
</script>

<style scoped>
    label {
        position:relative;
        display:inline-block;
        padding: 0;
        height: fit-content;
        margin-top: 20px;
        max-width: 300px;
        outline: none;
        min-height: 60px;
        line-height: 1.6!important;
    }

    span:not(.show_password) {
        padding:10px;
        pointer-events: none;
        position:absolute;
        left:0;
        top:10px;
        transition: 0.3s;
        transition-timing-function: ease;
        transition-timing-function: cubic-bezier(0.25, 0.1, 0.25, 1);
        opacity:0.5;
    }

    input {
        height: 60px;
        width: 100%;
        outline: none;
        padding: 0 12px;
    }

    input:focus + span, input:not(:placeholder-shown) + span {
        opacity:1;
        transform: scale(0.75) translateY(-140%) translateX(-20%);
    }

    input:focus + span, input:not(:-ms-input-placeholder) + span {
        opacity:1;
        transform: scale(0.75) translateY(-140%) translateX(-20%);
    }
    .show_password {
        position: absolute;
        background-image: url("/storage/show_password.png");
        background-size: contain;
        content: "";
        display: inline-block;
        cursor: pointer;
        width: 26px;
        height: 26px;
        background-repeat: no-repeat;
        top: 18px;
    }
</style>

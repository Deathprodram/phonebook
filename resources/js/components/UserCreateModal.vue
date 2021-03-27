<template>
    <div id="user-create" class="modal fade" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 v-if="!formUserData.isEdit" class="modal-title">Создать пользователя</h4>
                    <h4 v-else class="modal-title">Редактировать пользователя</h4>
                </div>

                <div class="modal-body">
                    <div v-show="sendError.isError" class="error-mess">
                        <p>{{ sendError.mess }}</p>
                    </div>

                    <form v-on:submit="sendUserData">
                        <input type="text" class="form-control" name="fullname" v-model="formUserData.name" placeholder="ФИО">
                        <div class="row phone-forms" v-for="n in phoneCount">
                            <div class="col-sm-3">
                                <select class="form-control"
                                        v-model="formUserData.phoneData[(n-1)].country_id"
                                        v-bind:class="[formUserData.phoneData[(n-1)].isError ? 'error-line' : '', 'form-control']">
                                    <option v-for="country in countries" :value="country.id">
                                        {{ country.shortcode +' - '+ country.phone_code }}
                                    </option>
                                </select>
                            </div>
                            <div class="col-sm-7">
                                <input type="text"
                                       v-bind:class="[formUserData.phoneData[(n-1)].isError ? 'error-line' : '', 'form-control']"
                                       name="phone[]"
                                       v-model="formUserData.phoneData[(n-1)].phone"
                                       placeholder="Номер телефона">
                            </div>
                            <div class="col-sm-2">
                                <button type="button"
                                        class="btn btn-success"
                                        v-show="!formUserData.isEdit"
                                        v-if="(n == 1)"
                                        v-on:click="addPhoneDataForm()">
                                    <span class="la la-plus"></span>
                                </button>
                                <button type="button"
                                        class="btn btn-danger"
                                        v-else
                                        v-on:click="removePhoneDataForm(n)">
                                    <span class="la la-close"></span>
                                </button>
                            </div>
                        </div>
                        <hr>
                        <button type="submit"
                                class="btn btn-block btn-success"
                                :disabled="submitButtonDisabled">Сохранить</button>
                    </form>

                </div>
            </div>

        </div>
    </div>
</template>

<script>

    export default {
        name: "UserCreateModal",
        props: {
            formUserData: {},
        },
        data() {
            return {
                countries: [],
                countrySelectOptions: [],
                phoneCount: 1,
                sendError: {
                    isError: false,
                    mess: '',
                },
                submitButtonDisabled: false,
            }
        },
        beforeMount() {
            axios.get('/country/list')
                .then(v => {
                    this.countries = v.data
                })
        },
        methods: {
            addPhoneDataForm: function() {
                if (this.phoneCount <= 10) {
                    this.phoneCount += 1
                    this.formUserData.phoneData.push({
                        country_id: '',
                        phone: '',
                    });
                }
            },
            removePhoneDataForm: function(index) {
                this.phoneCount -= 1
                delete this.formUserData.phoneData.splice((index - 1), 1);
            },
            // send data to create
            sendUserData: function(e) {
                e.preventDefault()
                if (this.validateUserData()) {
                    let url = !this.formUserData.isEdit
                        ? '/phonebook/create'
                        : '/phonebook/edit'
                    this.submitButtonDisabled = true
                    axios.post(url, this.formUserData)
                        .then(v => {
                            $('#user-create').modal('hide')
                            window.location.reload()
                        })
                }
            },
            // Validate send data
            validateUserData: function () {
                if (this.formUserData.name == '') {
                    return this.setValidateErrorData('Имя не может быть пустым')
                }
                for (let data in this.formUserData.phoneData) {
                    this.formUserData.phoneData[data].isError = false
                    if (this.formUserData.phoneData[data].country_id == '') {
                        this.formUserData.phoneData[data].isError = true
                        return this.setValidateErrorData('Не выбран код страны для номера')
                    }
                    if (this.formUserData.phoneData[data].phone == '') {
                        this.formUserData.phoneData[data].isError = true
                        return this.setValidateErrorData('Номер телефона не можеть быть пустым')
                    }
                    let reg = new RegExp('^\\d+$')
                    if (!reg.test(this.formUserData.phoneData[data].phone)) {
                        this.formUserData.phoneData[data].isError = true
                        return this.setValidateErrorData('Номер телефона должен состоять только из цифр')
                    }
                    delete this.formUserData.phoneData[data].isError
                }
                this.sendError.isError = false
                return true
            },
            setValidateErrorData: function(text) {
                this.sendError.isError = true
                this.sendError.mess = text
                return false
            },
        },
    }
</script>

<style scoped>
    form {
        overflow: hidden;
    }
    form > input, form > div{
        margin-top: 10px;
    }
    .phone-forms {
        padding: 0;
    }
    .phone-forms > select, .phone-forms > input {
        margin-top: 10px;
    }
    .error-mess {
        background-color: red;
        padding: 5px;
    }
    .error-line {
        border-color: red;
    }
</style>

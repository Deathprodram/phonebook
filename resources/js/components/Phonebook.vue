<template>
    <div class="card">
        <div class="card-header">
            <h4>Телефонный справочник</h4>
            <button
                type="button"
                class="btn btn-success"
                data-toggle="modal"
                data-target="#user-create">Добавить</button>
        </div>
        <table class="table table-bordered">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">
                    <a href="#" v-on:click="sortPhoneBookData('user', $event )">Пользователь</a>
                    <input type="search" class="form-control" v-model="searchUser">
                </th>
                <th scope="col">
                    <a href="#" v-on:click="sortPhoneBookData('country', $event )">Страна</a>
                    <input type="search" class="form-control" v-model="searchCountry">
                </th>
                <th scope="col">
                    Номер телефона
                    <input type="search" class="form-control" v-model="searchPhone">
                </th>
                <th scope="col">Действия</th>
            </tr>
            </thead>
            <tbody>
                <tr v-for="(data, key) in phoneBookData">
                    <td>{{ ( (pageConfig.page * pageConfig.count) - (pageConfig.count - key - 1) ) }}</td>
                    <td>{{ data.user.name }}</td>
                    <td>{{ data.country.name }}</td>
                    <td>{{ data.country.phone_code +' '+ data.phone }}</td>
                    <td>
                        <button type="button"
                                class="btn btn-primary"
                                v-on:click="editPhoneBookData(data)">
                            <span class="la la-edit"></span>
                        </button>
                        <button type="button"
                                class="btn btn-danger"
                                v-on:click="destroyPhoneBookData(data.id)">
                            <span class="la la-trash-o"></span>
                        </button>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="col-sm-8 col-offset-sm-4">
            <ul class="pagination pg-primary">
                <li v-bind:class="[(pageConfig.page == n) ? 'active' : '', 'page-item']"
                    class="page-item"
                    v-for="n in pageConfig.allPages">
                    <a class="page-link" href="#" v-on:click="getPageByNum(n, $event)">{{ n }}</a>
                </li>
            </ul>
        </div>

        <user-create-modal :form-user-data="fieldData"></user-create-modal>
    </div>
</template>

<script>
    import UserCreateModal from './UserCreateModal';

    export default {
        name: "Phonebook",
        components: {
            UserCreateModal,
        },
        data() {
            return {
                pageConfig: {
                    page: 1,
                    count: 10,
                    allCount: 0,
                    allPages: 0,
                },
                fieldData: {
                    name: '',
                    phoneData: [{
                        country_id: '',
                        phone: '',
                    }],
                    isEdit: false
                },
                phoneBookData: [],
                sortConfig: {
                    user: 'desc',
                    country: 'desc',
                },
                searchCountry: '',
                searchUser: '',
                searchPhone: '',
            }
        },
        beforeMount() {
            this.getPhoneBookData();
        },
        watch: {
            searchUser: function (query) {
                setTimeout(() => {
                    this.getPhoneBookData(this.pageConfig.page, null, ['user', query])
                }, 1000)
            },
            searchCountry: function (query) {
                setTimeout(() => {
                    this.getPhoneBookData(this.pageConfig.page, null, ['country', query])
                }, 1000)
            },
            searchPhone: function (query) {
                setTimeout(() => {
                    this.getPhoneBookData(this.pageConfig.page, null, ['phone', query])
                }, 1000)
            },
        },
        methods: {
            getPageByNum: function(pageNum, e) {
                e.preventDefault()
                this.getPhoneBookData(pageNum)
            },
            getPhoneBookData: function(pageNum = 1, sortBy = null, searchBy = null) {
                let params = {
                    page: pageNum,
                    sort_by: sortBy,
                    search: searchBy,
                }
                axios.get('/phonebook/list', {params: params})
                    .then(v => {
                        this.phoneBookData = v.data.phone_list
                        this.pageConfig.page = pageNum
                        this.pageConfig.allCount = v.data.data_count
                        this.pageConfig.allPages = Math.ceil(this.pageConfig.allCount / this.pageConfig.count)
                        console.log(v)
                    })
            },
            sortPhoneBookData: function(sortKey, e) {
                e.preventDefault()
                this.sortConfig[sortKey] = (this.sortConfig[sortKey] == 'asc')
                    ? 'desc' : 'asc'
                let sortBy = [sortKey, this.sortConfig[sortKey]]
                this.getPhoneBookData(this.pageConfig.page, sortBy)
            },
            editPhoneBookData: function(data) {
                this.fieldData = {
                    id: data.id,
                    name: data.user.name,
                    phoneData: [{
                        country_id: data.country.id,
                        phone: data.phone,
                    }],
                    isEdit: true,
                }
                $('#user-create').modal()
            },
            destroyPhoneBookData: function(id) {
                let conf = confirm('Вы точно хотите удалить?')
                if (conf) {
                    axios.delete('/phonebook/destroy', {
                        data: {id:id}
                    }).then(v => window.location.reload())
                }
            },
        },
    }

</script>

<style>
    .success-line {
        border-color: green;
    }
</style>

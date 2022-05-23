<template>
    <div>
        <Head title="Translator"/>
        <Heading class="mb-6">Translator</Heading>

        <Card class="flex flex-col items-center justify-center" style="min-height: 300px">
            <button @click="setLocale(locale)" v-for="locale in locales">Load <b>{{ locale }}</b></button>
            <br/>
            <button @click="update" class="btn btn-success">Update Translation</button>

            <table class="table-auto">
                <thead>
                <th>Key</th>
                <th>Translation</th>
                </thead>
                <tbody>
                <tr v-for="translate in keys" v-bind:key="translate.key">
                    <td>{{ translate.key }}</td>
                    <td>
                        <input type="text" v-model="translate.str">
                    </td>
                </tr>
                </tbody>
            </table>
        </Card>
    </div>
</template>

<script>

export default {
    data: () => ({
        search: null,
    }),

    props: {
        keys: Array,
        locales: Array,
        locale: '',
    },

    mounted() {
        console.log(this.keys, this.locale, this.locales)
    },

    methods: {
        update() {
            console.log("will update", this.keys);
            Nova.request()
                .post(`/nova-vendor/translator`, {
                    keys: this.keys,
                    locale: this.locale
                })
                .then(response => {
                    window.location=window.location.href;
                });
        },

        setLocale(locale) {
            window.location=window.location.href.replace(this.locale, locale);
        }
    }
}
</script>

<style>
/* Scoped Styles */

</style>

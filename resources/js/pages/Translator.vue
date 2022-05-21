<template>
    <div>
        <Head title="Str Translator"/>
        <Heading class="mb-6">{{ localize('Str Translator') }}</Heading>
        <Card class="flex flex-col items-center justify-center" style="min-height: 300px">
            <SelectControl
                :options="locales"
                v-model:selected="locale"
                @input="locale = $event.target.value"
                @change="receiveStr"/>


            <p class="dark:text-white text-lg opacity-70">
                {{ keys }}
                <code
                    class="ml-1 border border-gray-100 dark:border-gray-900 text-sm font-mono text-white bg-black rounded px-2 py-1"
                >
                    /nova-components/StrTranslator/resources/js/pages/Tool.vue
                </code>
            </p>
        </Card>
    </div>
</template>

<script>
import api from '../api';

export default {
    data: () => ({
        search: null,
        locale: 'en',
        locales: [],
        keys: [],
    }),

    mounted() {
        this.setupVariable();
    },

    methods: {
        receiveStr() {
            console.log(this.locales, this.locale, this.keys)
            return api.receiveStr(this.locale)
                .then(keys => this.keys = keys);
        },

        writeStr() {
            return api.writeStr(this.locale, this.keys)
        },

        availableLocales() {
            return api.availableLocales()
                .then(locales => this.locales = locales);
        },

        currentLocale() {
            return api.currentLocale()
                .then(locale => this.locale = locale);
        },

        setupVariable() {
            console.log("ciao");
            this.availableLocales();
            this.currentLocale();
        }
    }
}
</script>

<style>
/* Scoped Styles */
</style>

export default {
    currentLocale() {
        return Nova.request()
            .get(`/nova-vendor/translator/current-locale`)
            .then(response => response.data);
    },

    availableLocales() {
        return Nova.request()
            .get(`/nova-vendor/translator/available-locales`)
            .then(response => response.data);
    },

    receiveStr(locale = 'en',search=null) {
        return Nova.request()
            .get(
                search
                    ? `/nova-vendor/translator/receive/${locale}?search=${search}`
                    : `/nova-vendor/translator/receive/${locale}`
            )
            .then(response => response.data);
    },

    writeStr(locale = 'en', obj = {}) {
        return Nova.request()
            .post(`/nova-vendor/translator/write${locale}`, obj)
            .then(response => response.data);
    },
};

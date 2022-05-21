import Translator from './pages/Translator'

Nova.booting((app, store) => {
    Nova.inertia('Translator', Translator)
})

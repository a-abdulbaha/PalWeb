<script setup>
import {computed, ref, watch} from "vue";
import {debounce} from "lodash";
import {useUserStore} from "../stores/UserStore.js";

const UserStore = useUserStore();

const emit = defineEmits([
    'updateFilter'
]);

const props = defineProps({
    activeModel: String,
    filters: Object,
});

const searchInput = ref(null);

const focusInput = () => {
    searchInput.value?.focus();
};

defineExpose({
    focusInput,
});

const filters = ref({
    search: props.filters.search || '',
    match: props.filters.match || 'term',
    sort: props.filters.sort || '',
    pinned: props.filters.pinned || false,
    category: props.filters.category || '',
    attribute: props.filters.attribute || '',
    form: props.filters.form || '',
    singular: props.filters.singular || '',
    plural: props.filters.plural || '',
});

let previousFilters = {...filters.value};

const debounceEmit = debounce((key, value) => {
    emit("updateFilter", {filter: key, value});
}, 250);

watch(
    filters,
    (newFilters) => {
        for (const key in newFilters) {
            if (newFilters[key] !== previousFilters[key]) {
                debounceEmit(key, newFilters[key]);
                previousFilters[key] = newFilters[key];
            }
        }
    },
    {deep: true}
);

watch(
    props.filters,
    (newPropFilters) => {
        for (const key in newPropFilters) {
            if (newPropFilters[key] !== filters.value[key]) {
                filters.value[key] = newPropFilters[key];
                previousFilters[key] = newPropFilters[key];
            }
        }
    }
);

// watch(
//     () => filters.value.pinned,
//     (newPinned) => {
//         if (newPinned) {
//             if (filters.value.sort !== 'pinned') {
//                 filters.value.sort = 'pinned';
//             }
//         } else {
//             const defaultSort = props.activeModel === 'terms' ? 'alphabetical' : 'latest';
//             if (filters.value.sort !== defaultSort) {
//                 filters.value.sort = defaultSort;
//             }
//         }
//     }
// );

const hasAttribute = computed(() => {
    const allowedCategories = ['', 'verb', 'noun', 'adjective', 'determiner'];
    return allowedCategories.includes(filters.value.category);
});

const hasForm = computed(() => {
    const allowedCategories = ['', 'verb', 'noun', 'adjective', 'numeral'];
    const allowedSingPatterns = ['', 'ap', 'pp', 'vn'];
    const disallowedAttributes = ['pseudo', 'defect'];
    return (
        allowedCategories.includes(filters.value.category) &&
        allowedSingPatterns.includes(filters.value.singular) &&
        !disallowedAttributes.includes(filters.value.attribute)
    );
});

const hasSingular = computed(() => {
    const allowedCategories = ['', 'noun', 'adjective', 'numeral'];
    return allowedCategories.includes(filters.value.category);
});

const hasPlural = computed(() => {
    const allowedCategories = ['', 'noun', 'adjective'];
    const disallowedAttributes = ['collective', 'demonym', 'defect'];
    return (
        allowedCategories.includes(filters.value.category) &&
        !disallowedAttributes.includes(filters.value.attribute)
    );
});

const isRegular = computed(() => {
    const allowedSingPatterns = ['CiCiC', 'CCīC', 'relative', 'ia'];
    return allowedSingPatterns.includes(filters.value.singular);
});

const isCCC = computed(() => {
    const allowedSingPatterns = ['CLC', 'CvCC', 'CvCCe', 'CvCvC'];
    return allowedSingPatterns.includes(filters.value.singular);
});
</script>

<template>
    <div class="search-filters-container">
        <select v-model="filters.match">
            <option value="root">Match Root</option>
            <option value="term">Match Term</option>
            <option value="gloss">Match Gloss</option>
        </select>

        <div class="search-bar">
            <input
                ref="searchInput"
                v-model="filters.search"
                :class="{'persisting': filters.search.length}"
                type="text"
                placeholder="دوّر"
            />

            <button v-if="UserStore.isUser" @click="filters.pinned = !filters.pinned"
                    :class="{'unpinned': !filters.pinned}">
                <img src="/img/pin.svg" alt="Pin"/>
            </button>
        </div>

        <select v-model="filters.sort"
                :class="((activeModel === 'terms' && filters.sort !== 'alphabetical') || (activeModel !== 'terms' && filters.sort !== 'latest')) ? 'persisting' : ''"
                v-if="['terms', 'decks'].includes(activeModel)">
            <option value="alphabetical" v-if="activeModel === 'terms'">Alphabetical by Root</option>
            <option value="latest">by Latest</option>
            <option value="popular" v-if="activeModel === 'decks'">by Popularity</option>
            <option value="pinned" v-if="filters.pinned">by Pinned</option>
        </select>

        <div class="search-filters" v-if="activeModel === 'terms'">
            <select v-model="filters.category" :class="filters.category ? 'persisting' : ''">
                <option value="">Category</option>
                <option value="verb">Verbs</option>
                <option value="noun">Nouns</option>
                <option value="adjective">Adjectives</option>
                <option value="numeral">Numerals</option>
                <option value="adverb">Adverbs</option>
                <option value="preposition">Prepositions</option>
                <option value="conjunction">Conjunctions</option>
                <option value="determiner">Determiners</option>
                <option value="particle">Particles</option>
                <option value="phrase">Phrases</option>
                <option value="affix">Affixes</option>
            </select>
            <select v-model="filters.attribute" :class="filters.attribute ? 'persisting' : ''">
                <option value="">Attribute</option>
                <option
                    v-if="filters.category === '' || filters.category === 'noun' || filters.category === 'determiner'"
                    value="masculine">
                    Masculine
                </option>
                <option
                    v-if="filters.category === '' || filters.category === 'noun' || filters.category === 'determiner'"
                    value="feminine">
                    Feminine
                </option>
                <option
                    v-if="filters.category === '' || filters.category === 'noun' || filters.category === 'determiner'"
                    value="plural">
                    Plural
                </option>
                <option v-if="filters.category === '' || filters.category === 'noun'" value="collective">
                    Collective
                </option>
                <option
                    v-if="filters.category === '' || filters.category === 'noun' || filters.category === 'adjective'"
                    value="demonym">
                    Demonym
                </option>
                <option
                    v-if="(filters.category === '' || filters.category === 'adjective') && filters.form === ''"
                    value="defect">
                    Defect
                </option>
                <option v-if="(filters.category === '' || filters.category === 'verb') && filters.form === ''"
                        value="pseudo">
                    Pseudo
                </option>
                <option value="clitic">Clitic</option>
                <option value="idiom">Idiom</option>
            </select>
            <select v-model="filters.form" :class="filters.form ? 'persisting' : ''">
                <option value="">Form</option>
                <option value="1">Form 1</option>
                <template v-if="filters.category !== 'numeral' && filters.plural !== 'Cu22āC'">
                    <option value="2">Form 2</option>
                    <option value="3">Form 3</option>
                    <option value="4">Form 4</option>
                    <option value="5">Form 5</option>
                    <option value="6">Form 6</option>
                    <option value="7">Form 7</option>
                    <option value="8">Form 8</option>
                    <option value="9">Form 9</option>
                    <option value="X">Form X</option>
                    <option value="2Q">Form 2Q</option>
                    <option value="5Q">Form 5Q</option>
                </template>
            </select>
            <select v-model="filters.singular" :class="filters.singular ? 'persisting' : ''">
                <option value="">Singular</option>
                <optgroup v-if="filters.category === 'numeral'" label="Derived Terms">
                    <option value="ap">Act. Part.</option>
                </optgroup>
                <template v-if="filters.category !== 'numeral'">
                    <optgroup label="Derived Terms">
                        <option value="ap">Act. Part.</option>
                        <option value="pp">Pas. Part.</option>
                        <option value="nv">Verbal Noun</option>
                    </optgroup>
                    <optgroup label="Named Patterns">
                        <option value="relative">Relative Adj.</option>
                        <option value="ia">Intensive Adj.</option>
                        <option value="na">Nominalized Adj.</option>
                    </optgroup>
                    <optgroup label="CCC">
                        <option value="CLC">CLC(e)</option>
                        <option value="CvCC">CvCC</option>
                        <option value="CvCCe">CvCCe</option>
                        <option value="CvCvC">CvCvC(e)</option>
                        <option value="CiCiC">CiCiC</option>
                    </optgroup>
                    <optgroup label="CCLC">
                        <option value="CCāC">CCāC</option>
                        <option value="CCāCe">CCāCe</option>
                        <option value="CCīC">CCīC(e)</option>
                        <option value="CCūC">CCūC(e)</option>
                    </optgroup>
                    <optgroup label="CCCC">
                        <option value="CvCCvC">CvCCvC(e)</option>
                        <option value="maCCvC">maCCvC(e)</option>
                    </optgroup>
                    <optgroup label="CCCLC">
                        <option value="CvCCLC">CvCCLC(e)</option>
                        <option value="Ca22āC">Ca22āC(e)</option>
                        <option value="Ca22īC">Ca22īC</option>
                        <option value="Ca22ūC">Ca22ūC(e)</option>
                    </optgroup>
                </template>
            </select>
            <select v-model="filters.plural" :class="filters.plural ? 'persisting' : ''">
                <option value="">Plural</option>
                <optgroup v-if="filters.singular === '' || isRegular" label="sound">
                    <option value="-īn">-īn</option>
                    <option value="-āt">-āt</option>
                </optgroup>
                <optgroup label="CCC">
                    <template v-if="filters.singular === '' || isCCC">
                        <option value="CCāC">CCāC</option>
                        <option value="CCūC">CCūC</option>
                        <option value="ʔaCCāC">ʔaCCāC</option>
                        <option v-if="filters.singular === 'CvCCe'" value="CvCaC">CvCaC</option>
                    </template>
                </optgroup>
                <optgroup
                    v-if="filters.singular === '' || (filters.singular === 'CCāC' || filters.singular === 'CCīC')"
                    label="CCLC">
                    <template v-if="filters.singular !== 'CCīC'">
                        <option value="ʔaCCiCe">ʔaCCiCe</option>
                        <option value="CCīC">CCīC</option>
                    </template>
                    <template v-if="filters.singular !== 'CCāC'">
                        <option value="CCāC">CCāC</option>
                        <option value="CuCaCa">CuCaCa</option>
                    </template>
                    <option value="CuCuC">CuCuC</option>
                </optgroup>
                <optgroup v-if="!isRegular" label="CCCC">
                    <option value="CaCāCiC">CaCāCiC</option>
                </optgroup>
                <optgroup
                    v-if="filters.singular === '' || filters.singular === 'CvCCLC' || filters.singular === 'relative'"
                    label="CCCLC">
                    <option v-if="filters.singular !== 'relative'" value="CaCāCīC">CaCāCīC</option>
                    <option value="CaCāCCe">CaCāCCe</option>
                </optgroup>
                <optgroup v-if="filters.singular === '' || filters.singular === 'ap'" label="CLCC">
                    <option value="Cu22āC">Cu22āC</option>
                    <option value="CCāC">CCāC</option>
                </optgroup>
                <optgroup v-if="!isRegular" label="Other">
                    <option value="CvCCān">CvCCān</option>
                </optgroup>
            </select>
        </div>
    </div>
</template>

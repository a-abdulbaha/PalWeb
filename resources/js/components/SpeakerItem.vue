<script setup>
import {route} from "ziggy-js";
import {useUserStore} from "../stores/UserStore.js";

const UserStore = useUserStore();

const props = defineProps({
    speaker: Object,
});

const localeKey = (value) => value?.toString().toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '');
</script>

<template>
    <div class="speaker-data">
        <div class="speaker-data-head">
            <div>{{ $t('components.speaker.data-title') }}</div>
            <Link v-if="$page.component === 'Community/Users/Show'" :href="route('speaker.show', speaker.id)"
                  class="material-symbols-rounded">open_in_new</Link>
            <Link v-else-if="speaker.user.id === UserStore.user.id" :href="route('sound-booth.index')"
                  class="material-symbols-rounded">edit</Link>
        </div>
        <div class="speaker-data-row">
            <div>{{ $t('components.speaker.fields.dialect') }}</div>
            <div>{{ $t(`components.speaker.dialects.${localeKey(speaker.dialect.name)}`) }}</div>
        </div>
        <div class="speaker-data-row">
            <div>{{ $t('components.speaker.fields.location') }}</div>
            <div>{{ $t('components.speaker.location', {ar: speaker.location.name_ar, en: speaker.location.name_en}) }}</div>
        </div>
        <div class="speaker-data-row">
            <div>{{ $t('components.speaker.fields.fluency') }}</div>
            <div>{{ $t(`components.speaker.fluency.${speaker.fluency}`) }}</div>
        </div>
        <div class="speaker-data-row">
            <div>{{ $t('components.speaker.fields.gender') }}</div>
            <div>{{ $t(`components.speaker.gender.${speaker.gender}`) }}</div>
        </div>
        <div class="speaker-data-row">
            <div>{{ $t('components.speaker.fields.audios') }}</div>
            <div>{{ speaker.audios_count }}</div>
        </div>
    </div>
</template>

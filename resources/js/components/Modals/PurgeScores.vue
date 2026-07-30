<script setup>
import {reactive} from 'vue';
import {router} from '@inertiajs/vue3';
import {route} from 'ziggy-js';
import {useI18n} from "vue-i18n";
import {useNotificationStore} from "../../stores/NotificationStore.js";

const {t} = useI18n();
const NotificationStore = useNotificationStore();

const props = defineProps({
    scorable_type: {type: String, required: true},
    scorable_id: {type: Number, required: true},
});

const emit = defineEmits(['close']);

const form = reactive({
    scorable_type: props.scorable_type,
    scorable_id: props.scorable_id,
    older_than: null,
    except: [],
    processing: false,
});

const purgeScores = async () => {
    if (!confirm(t('score.notifications.purge-confirm'))) return;

    form.processing = true;

    try {
        const {data} = await axios.post(route('scores.purge'), {
            scorable_type: form.scorable_type,
            scorable_id: form.scorable_id,
            older_than: form.older_than,
            except: form.except,
        });

        if (data.success) {
            NotificationStore.addNotification(t('score.notifications.purge-success', {count: data.count}));
            emit('close');
            router.reload();
        }
    } finally {
        form.processing = false;
    }
};
</script>

<template>
    <div class="window-container modal-container">
        <div class="window-section-head">
            <h1>{{ $t('modals.purge-scores.title') }}</h1>
        </div>

        <form @submit.prevent="purgeScores">
            <div class="modal-container-body form-body">
                <p style="font-weight: 700">Delete all my Scores for this model<span v-if="form.older_than">
            older than one {{ form.older_than }}</span>
                    <span v-if="form.except.length">, except my
                <span v-if="form.except.includes('latest')">latest</span>
                <span v-if="form.except.length > 1"> & </span>
                <span v-if="form.except.includes('highest')">highest</span>
                Score</span>.
                </p>

                <div class="field-item">
                    <label for="older_than">{{ $t('modals.purge-scores.fields.time-limit') }}</label>
                    <select id="older_than" v-model="form.older_than">
                        <option :value="null">{{ $t('modals.purge-scores.time-limits.none') }}</option>
                        <option value="day">{{ $t('modals.purge-scores.time-limits.day') }}</option>
                        <option value="week">{{ $t('modals.purge-scores.time-limits.week') }}</option>
                        <option value="month">{{ $t('modals.purge-scores.time-limits.month') }}</option>
                        <option value="year">{{ $t('modals.purge-scores.time-limits.year') }}</option>
                    </select>
                </div>

                <div class="field-item">
                    <label>
                        <input type="checkbox" value="highest" v-model="form.except">
                        {{ $t('modals.purge-scores.keep.highest') }}
                    </label>
                    <label>
                        <input type="checkbox" value="latest" v-model="form.except">
                        {{ $t('modals.purge-scores.keep.latest') }}
                    </label>
                </div>
            </div>
            <div class="window-footer">
                <button type="submit" class="button-danger" :disabled="form.processing">
                    {{ $t('modals.purge-scores.submit') }}
                </button>
            </div>
        </form>
    </div>
</template>

import {computed, unref} from "vue";
import {useNotificationStore} from "../../stores/NotificationStore.js";
import {useI18n} from "vue-i18n";

export function useUser(user) {
    const {t} = useI18n();
    const NotificationStore = useNotificationStore();

    const resolvedUser = computed(() => unref(user) ?? null);

    const isAdmin = computed(() => resolvedUser.value?.roles?.includes('admin') ?? false);
    const isStudent = computed(() => resolvedUser.value?.roles?.includes('student') ?? false);
    const isUser = computed(() => !!resolvedUser.value);

    const highestRole = computed(() => {
        if (isAdmin.value) return 'admin';
        if (isStudent.value) return 'student';
        if (isUser.value) return 'pal';
        return 'guest';
    });

    const toggleStudentRole = async () => {
        const response = await axios.patch(route('api.users.roles.toggleStudent', resolvedUser.value.id));
        if (response.data.success) {
            resolvedUser.value.roles = response.data.user.roles;
            isStudent.value
                ? NotificationStore.addNotification(t('user.notifications.role-granted-success'))
                : NotificationStore.addNotification(t('user.notifications.role-revoked-success'));

        } else {
            NotificationStore.addNotification(t('user.notifications.role-toggle-error'), 'error');
        }
    };

    return {isAdmin, isStudent, isUser, highestRole, toggleStudentRole};
}

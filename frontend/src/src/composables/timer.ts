import { ref } from 'vue';

type FinishedCallback = () => void;

export const useTimer = (initialTime: number) => {
    const count = ref<number>(initialTime);

    const startCountdown = (callback?: FinishedCallback) => {
        count.value = initialTime;

        let interval = setInterval(() => {
            count.value -= 1;

            if (count.value <= 0) {
                clearInterval(interval);
                if (callback) {
                    callback();
                }
            }
        }, 1000);
    };

    return { count, startCountdown };
};

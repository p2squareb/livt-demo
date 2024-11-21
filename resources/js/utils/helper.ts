import { Ref, computed, ref } from 'vue';
import dayjs from 'dayjs';
import relativeTime from 'dayjs/plugin/relativeTime';
import 'dayjs/locale/ko'; // 한국어 로케일 추가

dayjs.extend(relativeTime);
//dayjs.locale('ko'); // 한국어 설정

/**
 * 체크박스 관련 기능을 제공하는 훅
 * @param items 체크박스 아이템 목록
 * @param idKey 각 아이템의 고유 ID를 나타내는 키 (기본값: 'id')
 * @returns 체크박스 관련 상태와 메서드
 */
export function useAllCheckboxes<T extends { [key: string]: any }>(items: Ref<T[]>, idKey: keyof T = 'id') {
    // 선택된 아이템의 ID를 저장하는 배열
    const selectedItemIds = ref<T[keyof T][]>([]);

    // 모든 아이템이 선택되었는지 확인하는 computed 속성
    const allChecked = computed(() => {
        return selectedItemIds.value.length === items.value.length;
    });

    // 모든 체크박스 선택/해제 토글 함수
    const toggleAllCheckboxes = () => {
        if (allChecked.value) {
            // 모두 선택된 상태면 모두 해제
            selectedItemIds.value = [];
        } else {
            // 모두 선택되지 않은 상태면 모두 선택
            selectedItemIds.value = items.value.map(item => item[idKey]);
        }
    };

    // 개별 체크박스 선택/해제 토글 함수
    const toggleCheckbox = (itemId: T[keyof T]) => {
        const index = selectedItemIds.value.indexOf(itemId);
        if (index > -1) {
            // 이미 선택된 아이템이면 선택 해제
            selectedItemIds.value.splice(index, 1);
        } else {
            // 선택되지 않은 아이템이면 선택
            selectedItemIds.value.push(itemId);
        }
    };

    return {
        selectedItemIds,
        allChecked,
        toggleAllCheckboxes,
        toggleCheckbox
    };
}

/**
 * 주어진 날짜를 상대적인 시간 또는 날짜 형식으로 포맷팅합니다.
 * @param date 포맷팅할 날짜 (문자열 또는 Date 객체)
 * @returns 포맷팅된 문자열
 */
export function formatRelativeTime(date: string | Date): string {
    const now = dayjs();
    const targetDate = dayjs(date);
    
    const diffSeconds = now.diff(targetDate, 'second');
    const diffMinutes = now.diff(targetDate, 'minute');
    const diffHours = now.diff(targetDate, 'hour');
    const diffDays = now.diff(targetDate, 'day');

    if (diffSeconds < 60) {
        return '방금 전';
    } else if (diffMinutes < 60) {
        return `${diffMinutes}분 전`;
    } else if (diffHours < 24) {
        return `${diffHours}시간 전`;
    } else if (diffDays < 4) {
        return `${diffDays}일 전`;
    } else {
        return targetDate.format('YYYY.MM.DD');
    }
}

export function goBack() {
    window.history.back();
}

export function formatNumberWithK(number: number): string {
    if (number >= 1000) {
        const formatted = (number / 1000).toFixed(number % 1000 === 0 ? 0 : 1);
        return `${formatted}k`;
    }
    return number.toString();
}

export function refInputClasses(custom: string) {
    let classes = '';
    if (custom === 'gray-7') {
        classes = 'border border-gray-300 dark:border-gray-600 bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-white dark:placeholder-gray-400 ';
    } else if (custom === 'gray-6') {
        classes = 'border border-gray-300 dark:border-gray-600 bg-gray-200 dark:bg-gray-600 text-gray-900 dark:text-white dark:placeholder-gray-400 ';
    }
    return classes;
}

export const calculateUntilDate = (periodType: string): { displayDate: string; untilDate: string } => {
    if (!periodType) {
        return { displayDate: '', untilDate: '' };
    }

    const today = new Date();
    
    if (periodType === 'eternity') {
        const futureDate = new Date(today.getFullYear() + 100, today.getMonth(), today.getDate());
        return {
            displayDate: '영구정지',
            untilDate: futureDate.toISOString().split('T')[0]
        };
    }
    
    const futureDate = new Date(today.setDate(today.getDate() + parseInt(periodType)));
    const formattedDate = futureDate.toISOString().split('T')[0];
    
    return {
        displayDate: formattedDate,
        untilDate: formattedDate
    };
};

export const limitTextLengthCount = (text: string, maxLength: number = 200): { limitedText: string; count: number } => {
    const limitedText = text.length > maxLength ? text.slice(0, maxLength) : text;
    return {
        limitedText,
        count: limitedText.length
    };
};

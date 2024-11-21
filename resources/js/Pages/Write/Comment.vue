<script setup lang="ts">
import axios from 'axios';
import { defineProps, ref } from 'vue'
import { useForm, usePage, router } from '@inertiajs/vue3'
import ProfileIcon from '@/Components/ProfileIcon.vue';
import ButtonColor from '@/Components/ButtonColor.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DialogModal from "@/Components/DialogModal.vue";
import Report from '@/Pages/Write/Partials/Report.vue';
import { formatRelativeTime } from "@/utils/helper";
import { PaginatedCommentList } from '@/types/board'

const props = defineProps<{
    comments: PaginatedCommentList;
    writeId: number;
    writerId: number;
    tableId: string;
    useRate: boolean;
}>()

const page = usePage();

const form = useForm({
    comment: '',
})

const commentSubmit = () => {
    if (form.comment.length === 0) return;
    form.post(route('write.comment.create', { 
        writeId: props.writeId, 
        tableId: props.tableId, 
    }), {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            form.reset()
        }
    });
}

const commentMore = async () => {
    try {
        const response = await axios.get(route('write.comment.list.more', { tableId: props.tableId, writeId: props.writeId }), {
            params: {
                page: props.comments.current_page + 1,
            }
        });
        if (response.data.comments) {
            props.comments.data.push(...response.data.comments.data);
            props.comments.current_page = response.data.comments.current_page;
            props.comments.next_page_url = response.data.comments.next_page_url;
        }
    } catch (error) {
        console.error('데이터를 불러오는 중 오류가 발생했습니다:', error);
    }
}

const activeReplyId = ref<number | null>(null);
const replyContent = ref('');

const toggleReplyForm = (commentId: number) => {
    activeReplyId.value = activeReplyId.value === commentId ? null : commentId;
    replyContent.value = '';
};

const cancelReply = () => {
    activeReplyId.value = null;
    replyContent.value = '';
};

const replySubmit = async (parentId: number) => {
    if (!replyContent.value) return;
    router.post(route('write.comment.reply.create', { writeId: props.writeId, tableId: props.tableId }), {
        replyComment: replyContent.value,
        parent_id: parentId
    }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            replyContent.value = '';
            activeReplyId.value = null;
        }
    });
};

const confirmingCommentDeletion = ref(false);
const activeCommentId = ref<number | null>(null);
const confirmCommentDeletion = (commentId: number) => {
    confirmingCommentDeletion.value = true;
    activeCommentId.value = commentId;
};

const closeCommentDeletionModal = () => {
    confirmingCommentDeletion.value = false;
    activeCommentId.value = null;
};

const deleteComment = () => {
    router.put(route('write.comment.delete', {tableId: props.tableId, writeId: props.writeId, commentId: activeCommentId.value}), {}, {
        preserveState: true, preserveScroll: true,
        onSuccess: () => {
            closeCommentDeletionModal();
        }
    });
};

const editingCommentId = ref<number | null>(null);
const editContent = ref('');

const toggleEditForm = (comment: any) => {
    editingCommentId.value = editingCommentId.value === comment.id ? null : comment.id;
    editContent.value = comment.comment;
};

const cancelEdit = () => {
    editingCommentId.value = null;
    editContent.value = '';
};

const editSubmit = (commentId: number) => {
    if (!editContent.value) return;
    router.put(route('write.comment.update', { 
        tableId: props.tableId, 
        writeId: props.writeId, 
        commentId: commentId 
    }), {
        comment: editContent.value
    }, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            editingCommentId.value = null;
            editContent.value = '';
        }
    });
};

const handleStatUpdate = (commentId: number, writerId: number, option: boolean) => {
    if (!page.props.auth.user) return;
    if (page.props.auth.user.id === writerId) return;

    router.post(
        route('write.rate.update'), 
        {
            targetType: 'comments', 
            targetId: commentId, 
            option 
        },
        {
            preserveState: true,
            preserveScroll: true,
        }
    );
};
</script>

<template>
    <article class="shadow-md">
        <div class="pb-3 border-b-2 border-gray-300">
            <h3 class="text-lg font-semibold"><span v-if="comments.total > 0">{{ comments.total }}</span> Comment</h3>
        </div>
        <div class="mt-4 pb-4 border-b border-gray-700">
            <ul class="space-y-5">
                <li v-for="(comment, cidx) in comments.data" :key="cidx" 
                    :class="[
                        'relative space-y-5',
                        comment.depth > 0 ? 'ml-8 pl-9' : 'pl-9' 
                    ]"
                >
                    <div class="flex items-start">
                        <div class="absolute top-0 left-0">
                            <ProfileIcon :profilePhotoPath="comment.user?.profile_photo_path" />
                        </div>
                        <div class="w-full">
                            <div class="grow space-y-2 mb-2">
                                <div class="flex items-center justify-between">
                                    <p>
                                        {{ comment.writer }} 
                                        <span class="text-gray-600 dark:text-gray-400 mx-2">|</span>
                                        <span class="text-gray-600 dark:text-gray-400 text-sm">{{ formatRelativeTime(comment.created_at) }}</span>
                                        <span 
                                            v-if="comment.user_id === props.writerId"
                                            class="bg-yellow-100 text-yellow-800 text-xs font-medium ml-2 px-1 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300"
                                        >작성자</span>
                                    </p>
                                    <div class="flex items-center justify-end">
                                        <Dropdown :align="'right'" :width="'24'" v-if="!comment.is_delete && page.props.auth.user">
                                            <template #trigger>
                                                <button class="-mt-1 cursor-pointer">
                                                    <Icon icon="dots-vertical" :class="'w-5 h-5 text-gray-700 dark:text-gray-300'"/>
                                                </button>
                                            </template>
                                            <template #content>
                                                <div class="flex flex-col px-4 py-2 space-y-2">
                                                    <Report :writerId="comment.user_id" :targetId="comment.id" targetType="comment" />
                                                    <p v-if="comment.user_id === page.props.auth.user?.id" 
                                                        @click="toggleEditForm(comment)" 
                                                        class="flex items-center cursor-pointer"
                                                    >
                                                        <Icon icon="edit" :class="'w-5 h-5 text-gray-700 dark:text-gray-300 items-center'"/>
                                                        <span class="ml-1 dark:text-gray-100">수정</span>
                                                    </p>
                                                    <p v-if="comment.user_id === page.props.auth.user?.id" @click="confirmCommentDeletion(comment.id)" class="flex items-center cursor-pointer">
                                                        <Icon icon="delete" :class="'w-5 h-5 text-gray-700 dark:text-gray-300 items-center'"/>
                                                        <span class="ml-1 dark:text-gray-100">삭제</span>
                                                    </p>
                                                </div>
                                            </template>
                                        </Dropdown>
                                    </div>
                                </div>   
                                <template v-if="editingCommentId === comment.id">
                                    <form @submit.prevent="editSubmit(comment.id)">
                                        <textarea 
                                            v-model="editContent"
                                            class="w-full focus:ring-0 border border-gray-300 dark:border-gray-600 text-md rounded-md p-2 bg-gray-50 text-gray-900 dark:bg-gray-800 dark:text-white" 
                                            rows="3"
                                        ></textarea>
                                        <div class="flex justify-end space-x-2 mt-2">
                                            <ButtonColor @click="cancelEdit" type="button" class="px-4 py-[5px] font-medium" :color="'alternative'">취소</ButtonColor>
                                            <ButtonColor type="submit" class="px-4 py-[5px] font-medium" :color="'alternative'">수정 완료</ButtonColor>
                                        </div>
                                    </form>
                                </template>
                                <p v-else-if="comment.is_delete" class="whitespace-pre-line text-gray-700 dark:text-gray-300">[삭제된 댓글입니다]</p>
                                <p v-else class="whitespace-pre-line" v-html="comment.comment" />
                            </div>
                            <div v-if="!comment.is_delete" class="flex flex-wrap">
                                <div v-if="props.useRate" class="flex items-center after:block after:content-['·'] last:after:content-[''] after: after:text-gray-900 after:dark:text-gray-100 after:px-2">
                                    <span 
                                        @click="handleStatUpdate(comment.id, comment.user_id, true)"
                                        class="inline-flex items-center text-gray-600 dark:text-gray-400 cursor-pointer"
                                        :class="{ 'text-green-500 dark:text-green-500': comment.user_rate_up, }"
                                    >
                                        <Icon icon="smile" class="w-5 h-5 mr-1"/>{{ comment.rate_up }}
                                    </span>
                                    <span 
                                        @click="handleStatUpdate(comment.id, comment.user_id, false)"
                                        class="inline-flex items-center text-gray-600 dark:text-gray-400 cursor-pointer ml-3"
                                        :class="{ 'text-red-500 dark:text-red-500': comment.user_rate_down }"
                                    >
                                        <Icon icon="angry" class="w-5 h-5 mr-1"/>{{ comment.rate_down }}
                                    </span>
                                </div>
                                <div v-if="$page.props.auth.user" class="flex items-center">
                                    <p @click="toggleReplyForm(comment.id)" class="font-medium cursor-pointer text-gray-700 hover:text-blue-500 dark:text-gray-300 dark:hover:text-blue-500">답글 남기기</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="activeReplyId === comment.id" class="mt-4 ml-4 p-3 rounded-lg bg-gray-50 dark:bg-gray-800">
                        <form @submit.prevent="replySubmit(comment.id)">
                            <div class="flex flex-col">
                                <div class="mb-1 text-gray-700 dark:text-gray-300">
                                    <span class="font-semibold">{{ page.props.auth.user?.nickname }}</span> 님의 답글
                                </div>
                                <div class="grow">
                                    <textarea v-model="replyContent" class="w-full focus:ring-0 border-none text-md rounded-md p-0 bg-gray-50 text-gray-900 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400" rows="3" placeholder="답글을 입력해주세요."></textarea>
                                </div>
                            </div>
                            <div class="flex justify-end space-x-2 mt-2">
                                <ButtonColor @click="cancelReply" type="button" class="px-4 py-[5px] font-medium" :color="'alternative'">취소</ButtonColor>
                                <ButtonColor type="submit"class="px-4 py-[5px] font-medium" :color="'alternative'">답글 등록</ButtonColor>
                            </div>
                        </form>
                    </div>
                </li>
            </ul>
            <div v-if="comments.next_page_url" class="text-center mt-5">
                <ButtonColor 
                    @click="commentMore" 
                    class="inline-flex w-full px-5 py-[9px] text-md font-medium text-center justify-center" 
                    :color="'alternative'"
                >
                    댓글 더보기
                </ButtonColor>
            </div>
        </div>
        <div v-if="$page.props.auth.user" class="mt-3 p-3 rounded-lg bg-gray-50 dark:bg-gray-800">
            <form @submit.prevent="commentSubmit">
                <div class="flex flex-col">
                    <div class="mb-1 text-gray-700 dark:text-gray-300"><span class="font-semibold">{{ page.props.auth.user?.nickname }}</span> 님의 댓글</div>
                    <div class="grow">
                        <textarea v-model="form.comment" class="w-full focus:ring-0 border-none text-md rounded-md p-0 bg-gray-50 text-gray-900 dark:bg-gray-800 dark:text-white dark:placeholder-gray-400" rows="3" placeholder="댓글을 입력해주세요."></textarea>
                    </div>
                </div>
                <div class="text-right">
                    <ButtonColor 
                        type="submit"
                        class="inline-flex w-full px-5 py-[5px] font-medium text-center justify-center sm:w-auto" 
                        :color="'alternative'"
                    >댓글 남기기</ButtonColor>
                </div>
            </form>
        </div>
    </article>
    <DialogModal :show="confirmingCommentDeletion" :max-width="'sm'" @close="closeCommentDeletionModal">
        <div class="p-6 bg-white dark:bg-gray-800 flex flex-col items-center">
            <h2 class="text-lg font-medium">댓글을 삭제하시겠습니까?</h2>
            <div class="mt-3 flex justify-center">
                <ButtonColor @click="closeCommentDeletionModal" class="mt-2 mr-3 px-5 py-[7px] text-md font-medium text-center" :color="'gray'">취소하기</ButtonColor>
                <ButtonColor @click="deleteComment" class="mt-2 px-5 py-[7px] text-md font-medium text-center" :color="'blue'">삭제하기</ButtonColor>
            </div>
        </div>
    </DialogModal>
</template>
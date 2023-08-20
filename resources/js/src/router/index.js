import {createRouter, createWebHistory } from 'vue-router';

import ChallengeListView from '../views/ChallengeListView.vue';
import ForgotPassView from '../views/ForgotPassView.vue';
import InquiryView from '../views/InquiryView.vue';
import LoginView from '../views/LoginView.vue';
import MemberRegisterView from '../views/MemberRegisterView.vue';
import MypageView from '../views/MypageView.vue';
import MyStepListView from '../views/MyStepListView.vue';
import PassEditView from '../views/PassEditView.vue';
import ProfEditView from '../views/ProfEditView.vue';
import ProfileView from '../views/ProfileView.vue';
import SmallStepDetailView from '../views/SmallStepDetailView.vue';
import StepDetailView from '../views/StepDetailView.vue';
import StepEditView from '../views/StepEditView.vue';
import StepSearchView from '../views/StepSearchView.vue';
import StepRegisterView from '../views/StepRegisterView.vue';

const routes = [
  {
    path: '/steps',
    name: 'StepSearch',
    component: StepSearchView,
  },
  {
    path: '/steps/:id/show',
    name: 'StepDetail',
    component: StepDetailView,
  },
  {
    path: '/steps/:id/show/:step',
    name: 'SmallStepDetail',
    component: SmallStepDetailView,
  },
  {
    path: '/profile/:id',
    name: 'Profile',
    component: ProfileView,
  },
  {
    path: '/login',
    name: 'Login',
    component: LoginView,
  },
  {
    path: '/register',
    name: 'MemberRegister',
    component: MemberRegisterView,
  },
  {
    path: '/forgot-password',
    name: 'ForgotPass',
    component: ForgotPassView,
  },
  {
    path: '/reset-password/:token+',
    name: 'ResetPass',
    component: () => import('../views/ResetPassView.vue'),
  },
  {
    path: '/users',
    name: 'Mypage',
    component: MypageView,
  },
  {
    path: '/users/password',
    name: 'PassEdit',
    component: PassEditView,
  },
  {
    path: '/users/profile',
    name: 'ProfEdit',
    component: ProfEditView,
  },
  {
    path: '/users/withdraw',
    name: 'Withdraw',
    component: () => import('../views/WithdrawView.vue'),
  },
  {
    path: '/steps/new',
    name: 'StepRegister',
    component: StepRegisterView,
  },
  {
    path: '/users/steps',
    name: 'MyStepList',
    component: MyStepListView,
  },
  {
    path: '/users/steps/:id/edit',
    name: 'StepEdit',
    component: StepEditView,
  },
  {
    path: '/users/challenges',
    name: 'ChallengeList',
    component: ChallengeListView,
  },
  {
    path: '/inquiry',
    name: 'Inquiry',
    component: InquiryView
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router;
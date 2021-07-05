import Application from '../../../../backend-module/resources/assets/js/resources/Application';
import POS from './resources/POS';
import Payment from './resources/Payment';

Application.register('pos', POS);
Application.register('payment', Payment);

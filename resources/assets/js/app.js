import Application from '../../../../backend-module/resources/assets/js/resources/Application';
import PointOfSale from './resources/PointOfSale';
import Payment from './resources/Payment';

Application.register('pointofsale', PointOfSale);
Application.register('payment', Payment);

PointOfSale.printable();

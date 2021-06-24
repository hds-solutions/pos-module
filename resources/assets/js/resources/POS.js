import Event from '../../../../../backend-module/resources/assets/js/utils/consoleevent';
import Document from '../../../../../backend-module/resources/assets/js/resources/Document';
import OrderLine from './OrderLine';
import PaymentLine from './PaymentLine';

export default class POS extends Document {

    constructor() {
        super();
        this.total = document.querySelector('[name="total"]');
    }

    _getContainerInstance(container) {
        switch (true) {
            case container.classList.contains('line-container'):
                return new OrderLine(this, container);
            case container.classList.contains('payment-container'):
                return new PaymentLine(this, container);
        }
        return null;
    }

}

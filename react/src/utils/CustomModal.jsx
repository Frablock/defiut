import { Button, Modal, ModalBody, ModalFooter, ModalHeader } from "reactstrap";

export default function CustomModal(props) {
    return(
        <Modal centered={true} isOpen={props.modalActive} toggle={() => props.setModalActive((state) => !state)}>
            <ModalHeader>{props.modalHeader}</ModalHeader>
            <ModalBody>
            {props.modalContent}
            </ModalBody>
            <ModalFooter>
            <Button color="primary" onClick={props.modalOnClick}>
                {props.modalButtonText}
            </Button>
            </ModalFooter>
        </Modal>
    )
}
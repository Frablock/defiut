import { Button, Modal, ModalBody, ModalFooter, ModalHeader } from "reactstrap";

export default function CustomModal(props) {
    return(
        <Modal isOpen={props.modalActive} toggle={() => props.modalOnClick()}>
            <ModalHeader>{props.modalHeader}</ModalHeader>
            <ModalBody>
            {props.modalContent}
            </ModalBody>
            <ModalFooter>
            <Button color="primary" onClick={() => props.modalOnClick()}>
                Fermer
            </Button>
            </ModalFooter>
        </Modal>
    )
}
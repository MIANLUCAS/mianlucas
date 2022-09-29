// #region [Imports] ===================================================================================================

// Libraries
import React, { useState } from "react";
import { Typography, Spin, Row, Col, Divider, Popover } from "antd";
import { QuestionCircleOutlined, LoadingOutlined } from "@ant-design/icons";
import { validateURL } from "../../../helpers/utils";

// Styles
import "./index.scss";

// Components
import InputSwitch from "./InputSwitch";

// #endregion [Imports]

// #region [Variables] =================================================================================================

declare var wp: any;
declare var acfwAdminApp: any;
const { Text } = Typography;

// #endregion [Variables]

// #region [Interfaces] ================================================================================================

interface IProps {
  field: any;
}

// #endregion [Interfaces]

// #region [Component] =================================================================================================

const SettingField = (props: IProps) => {
  const { field } = props;
  const { id, title, type, desc, desc_tip } = field;
  const { validation } = acfwAdminApp;
  const [showSpinner, setShowSpinner]: [boolean, any] = useState(false);
  const [invalidInput, setInvalidInput]: [boolean, any] = useState(false);

  const tooltip = desc_tip ? (
    <div className="setting-tooltip-content">{desc_tip}</div>
  ) : null;

  if ("title" === type) {
    return (
      <div className="form-heading">
        <h1>{title}</h1>
        <p>{desc}</p>
      </div>
    );
  }

  if ("sectionend" === type) return null;

  const validateInput = (value: unknown) => {
    // validate url value.
    if (value && type === "url" && !validateURL(value + "")) {
      setInvalidInput(true);
      return false;
    }

    setInvalidInput(false);
    return true;
  };

  return (
    <Row gutter={16} className="form-control" id={`${id}_field`} key={id}>
      <Divider />
      <Col span={8}>
        <label>
          <strong>{title}</strong>
        </label>
        {desc_tip ? (
          <Popover placement="right" content={tooltip} trigger="click">
            <QuestionCircleOutlined className="setting-tooltip-icon" />
          </Popover>
        ) : null}
      </Col>
      <Col className="setting-field-column" span={16}>
        <InputSwitch
          field={field}
          setShowSpinner={setShowSpinner}
          validateInput={validateInput}
        />
        {showSpinner ? (
          <Spin indicator={<LoadingOutlined style={{ fontSize: 24 }} spin />} />
        ) : null}
        <div className={`invalid-input${invalidInput ? " show" : ""}`}>
          {invalidInput ? (
            <Text type="danger">
              {validation[type] ? validation[type] : validation.default}
            </Text>
          ) : null}
        </div>
        {desc ? (
          <p>
            <Text>{desc}</Text>
          </p>
        ) : null}
      </Col>
    </Row>
  );
};

export default SettingField;

// #endregion [Component]

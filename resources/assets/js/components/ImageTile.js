import React, { PureComponent } from 'react';
import PropTypes from 'prop-types';

export default class ImageTile extends PureComponent {
    constructor(props) {
        super(props);
    }
    render() {
        return (
            <div className="card" onClick={() => {
                this.props.onViewHandler(this.props.id);
            }}>
                <h4 className="card-title">{this.props.title}</h4>
                <img src={this.props.link} alt={this.props.title} className="card-image"/>
                <div className="card-body">
                    <div className="card-option">
                        <a onClick={() => { return false }} download href={this.props.link}><i className="fa fa-download" /> Download</a>
                        <span>Downloads: {this.props.downloads}</span>
                    </div>
                    <div className="card-option">
                        <a href={this.props.link}><i className="fa fa-eye" /> Show image</a>
                        <span>Views: {this.props.views}</span>
                    </div>
                </div>
            </div>
        );
    }
}


ImageTile.propTypes = {
    title: PropTypes.string,
    id: PropTypes.number.isRequired,
    link: PropTypes.string.isRequired,
    downloads: PropTypes.number.isRequired,
    views: PropTypes.number.isRequired,
    onViewHandler: PropTypes.func.isRequired
};
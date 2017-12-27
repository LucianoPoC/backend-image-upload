import React, { Component } from 'react';
import PropTypes from 'prop-types';

export default class ImageTile extends Component {
    render() {
        return (
            <div>
                <h1>{this.props.title}</h1>
                <img src={this.props.image} alt={this.props.title}/>
                <h3>{this.props.downloads}</h3>
                <h3>{this.props.views}</h3>
            </div>
        );
    }
}


ImageTile.propTypes = {
    title: PropTypes.string,
    image: PropTypes.string.isRequired,
    downloads: PropTypes.number.isRequired,
    views: PropTypes.number.isRequired
};